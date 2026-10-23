$file = "app/Views/admin/products.php"
$content = [System.IO.File]::ReadAllText($file, [System.Text.Encoding]::UTF8)

# Checkbox accents
$content = $content.Replace('accent-color: #d4ba88;', 'accent-color: #737B85;')
$content = $content.Replace('background: rgba(202, 161, 92, 0.1); border: 1px solid rgba(202, 161, 92, 0.25); border-radius: 8px; padding: 12px 16px; margin-top: 6px; font-size: 0.8rem; color: #d4ba88;', 'background: rgba(167, 173, 180, 0.1); border: 1px solid rgba(167, 173, 180, 0.3); border-radius: 8px; padding: 12px 16px; margin-top: 6px; font-size: 0.8rem; color: #CBD0D6;')
$content = $content.Replace('<h4 style="color: #34d399; margin: 0 0 10px; font-size: 0.95rem;">Como conectar com seu Bling:</h4>', '<h4 style="color: #FFFFFF; margin: 0 0 10px; font-size: 0.95rem;">Como conectar com seu Bling:</h4>')

# Platform link colors
$content = $content.Replace("const customColorHex = isCustomColor ? btnColor : '#d4ba88';", "const customColorHex = isCustomColor ? btnColor : '#CBD0D6';")
$content = $content.Replace("color: #d4ba88; font-size: 0.72rem; cursor: pointer; text-decoration: underline;", "color: #CBD0D6; font-size: 0.72rem; cursor: pointer; text-decoration: underline;")
$content = $content.Replace('<label style="font-size: 0.76rem; color: #d4ba88; display: flex;', '<label style="font-size: 0.76rem; color: #CBD0D6; display: flex;')
$content = $content.Replace('<label style="font-size: 0.76rem; color: #d4ba88;">📐 Tamanho</label>', '<label style="font-size: 0.76rem; color: #CBD0D6;">📐 Tamanho</label>')
$content = $content.Replace('<label style="font-size: 0.76rem; color: #d4ba88;">↗️ Abertura do Link</label>', '<label style="font-size: 0.76rem; color: #CBD0D6;">↗️ Abertura do Link</label>')
$content = $content.Replace('placeholder="#D4BA88"', 'placeholder="#CBD0D6"')
$content = $content.Replace('<option value="gold" ${btnColor === ''gold'' ? ''selected'' : ''''}>👑 Ouro Imperial (#D4BA88)</option>', '<option value="gold" ${btnColor === ''gold'' ? ''selected'' : ''''}>🪙 Prata Nobre Divinium (#A7ADB4)</option>')
$content = $content.Replace('<option value="dark" ${btnColor === ''dark'' ? ''selected'' : ''''}>🖤 Ônix Nobre (#121110)</option>', '<option value="dark" ${btnColor === ''dark'' ? ''selected'' : ''''}>🖤 Ônix & Grafite (#252525)</option>')

# JS Handlers
$content = $content.Replace("currentProductLinks[idx].button_color = '#d4ba88';", "currentProductLinks[idx].button_color = '#CBD0D6';")
$content = $content.Replace("bgStyle = 'linear-gradient(135deg, #d4ba88 0%, #b89758 100%)';`r`n        textColor = '#0d0c0b';", "bgStyle = 'linear-gradient(135deg, #CBD0D6 0%, #A7ADB4 100%)';`r`n        textColor = '#202020';`r`n        borderColor = '#737B85';")
$content = $content.Replace("bgStyle = 'linear-gradient(135deg, #d4ba88 0%, #b89758 100%)';`n        textColor = '#0d0c0b';", "bgStyle = 'linear-gradient(135deg, #CBD0D6 0%, #A7ADB4 100%)';`n        textColor = '#202020';`n        borderColor = '#737B85';")
$content = $content.Replace("borderColor = 'rgba(212, 186, 136, 0.6)';", "borderColor = '#737B85';")
$content = $content.Replace("borderColor = 'rgba(212, 186, 136, 0.4)';", "borderColor = 'rgba(167, 173, 180, 0.4)';")
$content = $content.Replace("lum > 160 ? '#121110' : '#ffffff'", "lum > 140 ? '#202020' : '#ffffff'")
$content = $content.Replace("resDiv.style.background = 'rgba(16, 185, 129, 0.15)';`r`n                resDiv.style.border = '1px solid rgba(16, 185, 129, 0.4)';`r`n                resDiv.style.color = '#34d399';", "resDiv.style.background = 'rgba(167, 173, 180, 0.15)';`r`n                resDiv.style.border = '1px solid rgba(167, 173, 180, 0.4)';`r`n                resDiv.style.color = '#FFFFFF';")
$content = $content.Replace("resDiv.style.background = 'rgba(16, 185, 129, 0.15)';`n                resDiv.style.border = '1px solid rgba(16, 185, 129, 0.4)';`n                resDiv.style.color = '#34d399';", "resDiv.style.background = 'rgba(167, 173, 180, 0.15)';`n                resDiv.style.border = '1px solid rgba(167, 173, 180, 0.4)';`n                resDiv.style.color = '#FFFFFF';")

[System.IO.File]::WriteAllText($file, $content, [System.Text.Encoding]::UTF8)
Write-Output "Products updated"
