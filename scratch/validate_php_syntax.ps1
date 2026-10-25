$files = Get-ChildItem -Path . -Recurse -Filter "*.php"
$errorsFound = @()

foreach ($file in $files) {
    if ($file.FullName -like "*\vendor\*") { continue }
    $code = Get-Content $file.FullName -Raw
    if ([string]::IsNullOrEmpty($code)) { continue }

    $len = $code.Length
    $i = 0
    $line = 1
    $col = 1

    $inPhp = $false
    $inSingleQuote = $false
    $inDoubleQuote = $false
    $inSingleLineComment = $false
    $inMultiLineComment = $false

    $braceStack = New-Object System.Collections.Generic.Stack[string]
    $parenStack = New-Object System.Collections.Generic.Stack[string]
    $bracketStack = New-Object System.Collections.Generic.Stack[string]

    while ($i -lt $len) {
        $c = $code[$i]
        $next = if ($i + 1 -lt $len) { $code[$i + 1] } else { [char]0 }

        if ($c -eq "`n") {
            $line++
            $col = 1
            if ($inSingleLineComment) { $inSingleLineComment = $false }
            $i++
            continue
        } else {
            $col++
        }

        if (-not $inPhp) {
            if ($c -eq '<' -and $next -eq '?') {
                if ($i + 4 -lt $len -and $code.Substring($i, 5) -eq "<?php") {
                    $inPhp = $true
                    $i += 5
                    $col += 4
                    continue
                } elseif ($i + 2 -lt $len -and $code.Substring($i, 3) -eq "<?=") {
                    $inPhp = $true
                    $i += 3
                    $col += 2
                    continue
                } elseif ($c -eq '<' -and $next -eq '?') {
                    $inPhp = $true
                    $i += 2
                    $col += 1
                    continue
                }
            }
            $i++
            continue
        }

        if ($inSingleLineComment) {
            if ($c -eq '?' -and $next -eq '>') {
                $inSingleLineComment = $false
                $inPhp = $false
                $i += 2
                continue
            }
            $i++
            continue
        }

        if ($inMultiLineComment) {
            if ($c -eq '*' -and $next -eq '/') {
                $inMultiLineComment = $false
                $i += 2
                continue
            }
            $i++
            continue
        }

        if ($inSingleQuote) {
            if ($c -eq '\' -and ($next -eq '\' -or $next -eq "'")) {
                $i += 2
                continue
            }
            if ($c -eq "'") {
                $inSingleQuote = $false
            }
            $i++
            continue
        }

        if ($inDoubleQuote) {
            if ($c -eq '\') {
                $i += 2
                continue
            }
            if ($c -eq '"') {
                $inDoubleQuote = $false
            }
            $i++
            continue
        }

        if ($c -eq '?' -and $next -eq '>') {
            $inPhp = $false
            $i += 2
            continue
        }

        if ($c -eq '/' -and $next -eq '/') {
            $inSingleLineComment = $true
            $i += 2
            continue
        }

        if ($c -eq '#' -and $next -ne '[') {
            $inSingleLineComment = $true
            $i++
            continue
        }

        if ($c -eq '/' -and $next -eq '*') {
            $inMultiLineComment = $true
            $i += 2
            continue
        }

        if ($c -eq "'") {
            $inSingleQuote = $true
            $i++
            continue
        }

        if ($c -eq '"') {
            $inDoubleQuote = $true
            $i++
            continue
        }

        if ($c -eq '{') {
            $braceStack.Push("$($line):$($col)")
        } elseif ($c -eq '}') {
            if ($braceStack.Count -gt 0) {
                [void]$braceStack.Pop()
            } else {
                $errorsFound += "$($file.FullName): Unmatched '}' at line $line, col $col"
            }
        } elseif ($c -eq '(') {
            $parenStack.Push("$($line):$($col)")
        } elseif ($c -eq ')') {
            if ($parenStack.Count -gt 0) {
                [void]$parenStack.Pop()
            } else {
                $errorsFound += "$($file.FullName): Unmatched ')' at line $line, col $col"
            }
        } elseif ($c -eq '[') {
            $bracketStack.Push("$($line):$($col)")
        } elseif ($c -eq ']') {
            if ($bracketStack.Count -gt 0) {
                [void]$bracketStack.Pop()
            } else {
                $errorsFound += "$($file.FullName): Unmatched ']' at line $line, col $col"
            }
        }

        $i++
    }

    if ($inSingleQuote) { $errorsFound += "$($file.FullName): Unclosed single quote at EOF" }
    if ($inDoubleQuote) { $errorsFound += "$($file.FullName): Unclosed double quote at EOF" }
    if ($inMultiLineComment) { $errorsFound += "$($file.FullName): Unclosed multi-line comment at EOF" }
    while ($braceStack.Count -gt 0) {
        $pos = $braceStack.Pop()
        $errorsFound += "$($file.FullName): Unclosed '{' opened at line:col $pos"
    }
    while ($parenStack.Count -gt 0) {
        $pos = $parenStack.Pop()
        $errorsFound += "$($file.FullName): Unclosed '(' opened at line:col $pos"
    }
    while ($bracketStack.Count -gt 0) {
        $pos = $bracketStack.Pop()
        $errorsFound += "$($file.FullName): Unclosed '[' opened at line:col $pos"
    }
}

if ($errorsFound.Count -eq 0) {
    Write-Host "SUCCESS: ALL PHP FILES PASSED DELIMITER/SYNTAX VALIDATION!" -ForegroundColor Green
} else {
    Write-Host "ERRORS FOUND ($($errorsFound.Count)):" -ForegroundColor Red
    foreach ($err in $errorsFound) {
        Write-Host $err -ForegroundColor Yellow
    }
}
