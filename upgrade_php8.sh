#!/bin/bash
# PHP 8 Compatibility Upgrade Script
# Batch replace deprecated functions

TARGET_DIR="htf"

echo "=== PHP 8 Compatibility Upgrade Script ==="
echo "Target directory: $TARGET_DIR"
echo ""

# Backup first
echo "[1/6] Creating backups..."
find "$TARGET_DIR" -name "*.php" -type f | while read file; do
    if [ ! -f "$file.php8bak" ]; then
        cp "$file" "$file.php8bak"
    fi
done
echo "✓ Backups created"

# Fix ereg() -> preg_match()
echo "[2/6] Replacing ereg() with preg_match()..."
find "$TARGET_DIR" -name "*.php" -type f | while read file; do
    # ereg("pattern", $var) -> preg_match("/pattern/", $var)
    sed -i 's/ereg("\([^"]*\)",/preg_match("\/\1\/",/g' "$file"
    sed -i "s/ereg('\([^']*\)',/preg_match('\/\1\/',/g" "$file"
    # eregi() case insensitive
    sed -i 's/eregi("\([^"]*\)",/preg_match("\/\1\/i",/g' "$file"
    sed -i "s/eregi('\([^']*\)',/preg_match('\/\1\/i',/g" "$file"
done
echo "✓ ereg() replaced"

# Fix split() -> explode()
echo "[3/6] Replacing split() with explode()..."
find "$TARGET_DIR" -name "*.php" -type f | while read file; do
    # Simple split() -> explode()
    sed -i 's/split(\([^,]*\),/explode(\1,/g' "$file"
done
echo "✓ split() replaced"

# Fix each() -> foreach
echo "[4/6] Fixing each() loops (manual review needed)..."
# This is complex and needs manual review, just flag them
find "$TARGET_DIR" -name "*.php" -type f -exec grep -l "each(" {} \; > each_files.txt
echo "✓ Files with each() listed in each_files.txt"

# Fix preg_replace /e modifier
echo "[5/6] Flagging preg_replace /e modifier..."
find "$TARGET_DIR" -name "*.php" -type f -exec grep -l "preg_replace.*\/.*e" {} \; > preg_e_files.txt
echo "✓ Files with /e modifier listed in preg_e_files.txt"

# Fix HTTP_* variables
echo "[6/6] Replacing HTTP_*_VARS with superglobals..."
find "$TARGET_DIR" -name "*.php" -type f | while read file; do
    sed -i 's/\$HTTP_POST_VARS/\$_POST/g' "$file"
    sed -i 's/\$HTTP_GET_VARS/\$_GET/g' "$file"
    sed -i 's/\$HTTP_SERVER_VARS/\$_SERVER/g' "$file"
    sed -i 's/\$HTTP_COOKIE_VARS/\$_COOKIE/g' "$file"
    sed -i 's/\$HTTP_POST_FILES/\$_FILES/g' "$file"
done
echo "✓ HTTP_*_VARS replaced"

echo ""
echo "=== Upgrade Summary ==="
echo "✓ Backups: *.php.php8bak"
echo "✓ ereg() -> preg_match()"
echo "✓ split() -> explode()"
echo "✓ HTTP_*_VARS -> \$_*"
echo ""
echo "⚠ Manual review needed:"
echo "  - each() loops (see each_files.txt)"
echo "  - preg_replace /e modifier (see preg_e_files.txt)"
echo ""
echo "Done!"
