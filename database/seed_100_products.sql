-- SEED DE 100 PRODUTOS DIVINIUM (HIGIENIZADO & CORRIGIDO)
SET NAMES utf8mb4;
UPDATE products SET name = REPLACE(name, 'ORGONOTE', 'ORGONITE'), description = REPLACE(description, 'orgonote', 'orgonite'), slug = REPLACE(slug, 'orgonote', 'orgonite') WHERE name LIKE '%ORGONOTE%' OR description LIKE '%orgonote%' OR slug LIKE '%orgonote%';
UPDATE products SET sku = TRIM(sku), parent_sku = TRIM(parent_sku);
UPDATE products SET image_url = '/assets/images/products/an-ov-40-capa.jpg' WHERE sku LIKE 'AN-OV-40%' AND (image_url LIKE '%bracelete%' OR image_url IS NULL OR image_url = '');
UPDATE products SET image_url = '/assets/images/products/an-rd-25-capa.jpg' WHERE sku LIKE 'AN-RD-25%' AND (image_url LIKE '%bracelete%' OR image_url LIKE '%an-ov-40%' OR image_url IS NULL OR image_url = '');
UPDATE products SET image_url = '/assets/images/products/an-rd-20-capa.jpg' WHERE sku LIKE 'AN-RD-20%' AND (image_url LIKE '%bracelete%' OR image_url IS NULL OR image_url = '');
UPDATE products SET image_url = '/assets/images/products/br-sf-06-capa.jpg' WHERE sku LIKE 'BR-SF-06%' AND (image_url LIKE '%bracelete%' OR image_url IS NULL OR image_url = '');

