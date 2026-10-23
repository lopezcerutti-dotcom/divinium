$file = "app/Views/admin/products.php"
$content = [System.IO.File]::ReadAllText($file, [System.Text.Encoding]::UTF8)

$content = $content.Replace('style="background: rgba(74, 222, 128, 0.12); color: #4ade80;"', 'style="background: rgba(167, 173, 180, 0.12); color: #CBD0D6;"')
$content = $content.Replace('style="background: rgba(202, 161, 92, 0.12); color: #d4ba88;"', 'style="background: rgba(167, 173, 180, 0.12); color: #CBD0D6;"')
$content = $content.Replace('style="background: rgba(16, 185, 129, 0.12); color: #34d399;"', 'style="background: rgba(167, 173, 180, 0.12); color: #CBD0D6;"')
$content = $content.Replace('style="color: #d4ba88; font-size: 0.72rem; margin-left: 4px;"', 'style="color: #CBD0D6; font-size: 0.72rem; margin-left: 4px;"')
$content = $content.Replace('style="font-size: 0.72rem; color: #34d399;"', 'style="font-size: 0.72rem; color: #CBD0D6;"')
$content = $content.Replace('color: #d4ba88; border-color: rgba(212,186,136,0.3);', 'color: #CBD0D6; border-color: rgba(167,173,180,0.35);')
$content = $content.Replace('background: rgba(212, 186, 136, 0.2); color: #d4ba88;', 'background: rgba(167, 173, 180, 0.2); color: #CBD0D6;')
$content = $content.Replace('style="color: #4ade80;"', 'style="color: #FFFFFF;"')
$content = $content.Replace('style="color: #d4ba88; font-weight: 600; margin-bottom: 10px;', 'style="color: #CBD0D6; font-weight: 600; margin-bottom: 10px;')
$content = $content.Replace("linear-gradient(135deg, #1f1c19 0%, #0d0c0b 100%)", "linear-gradient(135deg, #252525 0%, #1a1a1a 100%)")
$content = $content.Replace("linear-gradient(135deg, #d4ba88 0%, #b89758 100%)", "linear-gradient(135deg, #CBD0D6 0%, #A7ADB4 100%)")
$content = $content.Replace("textColor = '#0d0c0b';", "textColor = '#202020';")

# Replace option and labels
$lines = [System.IO.File]::ReadAllLines($file, [System.Text.Encoding]::UTF8)
for ($i = 0; $i -lt $lines.Length; $i++) {
    if ($lines[$i] -match '👑 Ouro Imperial') {
        $lines[$i] = $lines[$i] -replace '👑 Ouro Imperial \(#D4BA88\)', '🪙 Prata Nobre Divinium (#A7ADB4)'
    }
    if ($lines[$i] -match 'color: #d4ba88') {
        $lines[$i] = $lines[$i] -replace 'color: #d4ba88;', 'color: #CBD0D6;'
    }
}
$content = [string]::Join("`r`n", $lines)
[System.IO.File]::WriteAllText($file, $content, [System.Text.Encoding]::UTF8)
Write-Output "Products cleaned"
