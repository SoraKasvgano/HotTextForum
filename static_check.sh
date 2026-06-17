#!/bin/bash
# Static Code Analysis - PHP 8 Compatibility Check

echo "=== PHP 8 Static Code Analysis ==="
echo ""

# Check for deprecated functions
echo "[1/5] Checking for deprecated functions..."
echo "--------------------------------------"
DEPRECATED_FUNCS="ereg|eregi|split\(|mysql_|session_register|set_magic_quotes|each\("
FOUND=$(grep -rn "$DEPRECATED_FUNCS" htf --include="*.php" | grep -v ".php8bak" | grep -v ".bak" | wc -l)
echo "Found $FOUND potential issues"
if [ $FOUND -gt 0 ]; then
    echo "Details:"
    grep -rn "$DEPRECATED_FUNCS" htf --include="*.php" | grep -v ".php8bak" | grep -v ".bak" | head -10
    echo "..."
fi
echo ""

# Check for preg_replace /e modifier
echo "[2/5] Checking for preg_replace /e modifier..."
echo "--------------------------------------"
PREG_E=$(grep -rn "preg_replace.*['\"].*\/[a-z]*e" htf --include="*.php" | grep -v ".php8bak" | wc -l)
echo "Found $PREG_E potential issues"
if [ $PREG_E -gt 0 ]; then
    echo "Details:"
    grep -rn "preg_replace.*['\"].*\/[a-z]*e" htf --include="*.php" | grep -v ".php8bak" | head -5
fi
echo ""

# Check for short tags
echo "[3/5] Checking for short PHP tags..."
echo "--------------------------------------"
SHORT_TAGS=$(grep -rn "^<?" htf --include="*.php" | grep -v "<?php" | grep -v "<?=" | grep -v ".php8bak" | wc -l)
echo "Found $SHORT_TAGS short tags"
if [ $SHORT_TAGS -gt 0 ]; then
    echo "Details:"
    grep -rn "^<?" htf --include="*.php" | grep -v "<?php" | grep -v "<?=" | grep -v ".php8bak" | head -5
fi
echo ""

# Check for potential security issues
echo "[4/5] Checking for potential security issues..."
echo "--------------------------------------"
echo "- Checking for extract() usage..."
EXTRACT=$(grep -rn "extract\(" htf --include="*.php" | grep -v ".php8bak" | grep -v "EXTR_SKIP" | wc -l)
echo "  Found $EXTRACT potentially unsafe extract() calls"

echo "- Checking for eval() usage..."
EVAL=$(grep -rn "eval\(" htf --include="*.php" | grep -v ".php8bak" | wc -l)
echo "  Found $EVAL eval() calls"

echo "- Checking for system() usage..."
SYSTEM=$(grep -rn "system\(|exec\(|shell_exec\(|passthru\(" htf --include="*.php" | grep -v ".php8bak" | wc -l)
echo "  Found $SYSTEM system command calls"
echo ""

# Check file structure
echo "[5/5] File structure verification..."
echo "--------------------------------------"
echo "Total PHP files: $(find htf -name "*.php" -type f | grep -v ".php8bak" | wc -l)"
echo "Backup files: $(find htf -name "*.php8bak" -type f | wc -l)"
echo "Core files:"
echo "  - global.php: $([ -f htf/global.php ] && echo '✓' || echo '✗')"
echo "  - require/checkpass.php: $([ -f htf/require/checkpass.php ] && echo '✓' || echo '✗')"
echo "  - require/bbscode.php: $([ -f htf/require/bbscode.php ] && echo '✓' || echo '✗')"
echo "  - require/security.php: $([ -f htf/require/security.php ] && echo '✓' || echo '✗')"
echo ""

# Summary
echo "=== Summary ==="
echo "--------------------------------------"
TOTAL_ISSUES=$(($FOUND + $PREG_E + $SHORT_TAGS))
if [ $TOTAL_ISSUES -eq 0 ]; then
    echo "✅ No major compatibility issues found!"
else
    echo "⚠️  Found $TOTAL_ISSUES potential compatibility issues"
    echo "   Please review the details above"
fi

echo ""
echo "Security checks:"
if [ $EXTRACT -gt 0 ] || [ $EVAL -gt 0 ] || [ $SYSTEM -gt 0 ]; then
    echo "⚠️  Found potentially unsafe functions - manual review required"
else
    echo "✅ No obvious security issues detected"
fi

echo ""
echo "Analysis complete!"
