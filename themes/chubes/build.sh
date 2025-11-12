#!/bin/bash

# WordPress Theme Build Script
# Creates both zip file and directory for WordPress theme installation in /dist/

THEME_NAME="chubes"
BUILD_DIR="build"
DIST_DIR="dist"
ZIP_NAME="${THEME_NAME}.zip"
THEME_DIR="${THEME_NAME}"

echo "Building WordPress theme: $THEME_NAME"

# Create dist directory
mkdir -p "$DIST_DIR"

# Clean up previous temporary build directory
if [ -d "$BUILD_DIR" ]; then
    rm -rf "$BUILD_DIR"
fi

if [ -f "$DIST_DIR/$ZIP_NAME" ]; then
    rm "$DIST_DIR/$ZIP_NAME"
fi

if [ -d "$DIST_DIR/$THEME_DIR" ]; then
    rm -rf "$DIST_DIR/$THEME_DIR"
fi

# Create temporary build directory
mkdir -p "$BUILD_DIR/$THEME_NAME"

# Copy theme files to temporary build directory (exclude development/git files)
rsync -av --progress . "$BUILD_DIR/$THEME_NAME/" \
    --exclude='.git*' \
    --exclude='.DS_Store' \
    --exclude='build/' \
    --exclude='dist/' \
    --exclude='build.sh' \
    --exclude='who-is-chubes.md' \
    --exclude='CLAUDE.md' \
    --exclude='README.md' \
    --exclude='*.zip' \
    --exclude='assets/fonts/*.ttf'

# Create zip file in /dist/ from temporary build directory
cd "$BUILD_DIR"
zip -r "../$DIST_DIR/$ZIP_NAME" "$THEME_NAME/"
cd ..

# Clean up temporary build directory
rm -rf "$BUILD_DIR"

echo "✅ Theme packaged successfully:"
echo "    Zip file: $DIST_DIR/$ZIP_NAME (for WordPress admin or FTP upload)"