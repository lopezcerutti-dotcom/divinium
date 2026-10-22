$cssPath = "assets\css\app.css"
$newSec14 = [System.IO.File]::ReadAllText("scratch\section14_css.txt", [System.Text.Encoding]::UTF8)
$css = [System.IO.File]::ReadAllText($cssPath, [System.Text.Encoding]::UTF8)

$startMarker = ".product-detail-page {"
$endMarker = "/* ==============================================================================`r`n   RESPONSIVIDADE: COMPUTADORES, TABLETS & CELULARES"
if (-not $css.Contains($endMarker)) {
    $endMarker = "/* ==============================================================================`n   RESPONSIVIDADE: COMPUTADORES, TABLETS & CELULARES"
}

$idxStart = $css.IndexOf($startMarker)
$idxEnd = $css.IndexOf($endMarker)

if ($idxStart -eq -1) {
    Write-Error "Start marker not found"
    exit 1
}
if ($idxEnd -eq -1) {
    Write-Error "End marker not found"
    exit 1
}

$before = $css.Substring(0, $idxStart)
$after = $css.Substring($idxEnd)

$finalCss = $before + $newSec14 + "`r`n`r`n" + $after
[System.IO.File]::WriteAllText($cssPath, $finalCss, [System.Text.Encoding]::UTF8)
Write-Output "Successfully updated app.css section 14!"
