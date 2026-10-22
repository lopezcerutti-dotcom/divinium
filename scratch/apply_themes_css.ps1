$cssPath = "assets\css\app.css"
$newThemes = [System.IO.File]::ReadAllText("scratch\new_themes_css.txt", [System.Text.Encoding]::UTF8)
$css = [System.IO.File]::ReadAllText($cssPath, [System.Text.Encoding]::UTF8)

$startMarker = "html.theme-silver-noir,"
$endMarker = ".product-detail-page {"

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

$headerComment = "/* ==============================================================================`r`n   TEMAS PREDEFINIDOS DA DIVINIUM (VARIÁVEIS DINÂMICAS)`r`n   ============================================================================== */`r`n"
$productSectionHeader = "`r`n/* ==============================================================================`r`n   14. PÁGINA EXCLUSIVA DE DETALHES DO PRODUTO & CANAIS DE VENDA IMPARCIAIS`r`n   DIVINIUM QUIET LUXURY HIGH JEWELRY DESIGN SYSTEM`r`n   ============================================================================== */`r`n`r`n"

$before = $css.Substring(0, $idxStart)
# Remove the preceding comment if present
$commentIdx = $before.LastIndexOf("/* ==============================================================================")
if ($commentIdx -gt 0) {
    $before = $before.Substring(0, $commentIdx)
}

$after = $css.Substring($idxEnd)

$finalCss = $before + $headerComment + $newThemes + "`r`n`r`n" + $productSectionHeader + $after
[System.IO.File]::WriteAllText($cssPath, $finalCss, [System.Text.Encoding]::UTF8)
Write-Output "Successfully updated app.css themes block!"
