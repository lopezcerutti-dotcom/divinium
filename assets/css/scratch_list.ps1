$path = Join-Path (Get-Location).Path "assets\css\app.css"
$matches = Select-String -Path $path -Pattern '(background|color|border-color):\s*#[0-9a-fA-F]{3,6}[^;]*!important'
Write-Output ("Total matches: " + $matches.Count)
foreach ($m in $matches) {
    Write-Output ($m.LineNumber.ToString() + ": " + $m.Line.Trim())
}
