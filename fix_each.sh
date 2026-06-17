#!/bin/bash
# Fix each() function calls - PHP 8 compatibility

echo "=== Fixing each() function calls ==="

# Pattern 1: while (list($key,$value)=each($array))
# Replace with: foreach($array as $key => $value)
find htf -name "*.php" -type f | while read file; do
    # Pattern: while (list($key,$value)=each($array))
    perl -i -pe 's/while\s*\(\s*list\s*\(\s*\$(\w+)\s*,\s*\$(\w+)\s*\)\s*=\s*each\s*\(\s*\$(\w+)\s*\)\s*\)/foreach(\$$3 as \$$1 => \$$2)/g' "$file"

    # Pattern: while(list($key,$value) = each($array))
    perl -i -pe 's/while\s*\(\s*list\s*\(\s*\$(\w+)\s*,\s*\$(\w+)\s*\)\s*=\s*each\s*\(\s*\$(\w+)\s*\)\s*\)/foreach(\$$3 as \$$1 => \$$2)/g' "$file"
done

echo "✓ each() function calls replaced with foreach"
echo "Note: Please manually review the changes, especially loop logic"
