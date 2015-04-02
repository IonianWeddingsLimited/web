#!/bin/bash

find . -type f -name "*.php" | \
    (while read file; do
        iconv -f windows-1252 -t UTF-8 "$file" > "$file.new" && mv -f "$file.new" "$file"
    done);
