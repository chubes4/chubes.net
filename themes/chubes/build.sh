#!/bin/bash

# WordPress Theme Build Script
# Creates both zip file and directory for WordPress theme installation

THEME_NAME="chubes"
BUILD_DIR="build"
DIST_DIR="dist"
ZIP_NAME="${THEME_NAME}.zip"
THEME_DIR="${THEME_NAME}"

echo "Building WordPress theme: $THEME_NAME"

# Create dist directory
mkdir -p "$DIST_DIR"

# Clean up previous builds
if [ -d "$BUILD_DIR" ]; then
    rm -rf "$BUILD_DIR"
fi

if [ -f "$DIST_DIR/$ZIP_NAME" ]; then
    rm "$DIST_DIR/$ZIP_NAME"
fi

if [ -d "$DIST_DIR/$THEME_DIR" ]; then
    rm -rf "$DIST_DIR/$THEME_DIR"
fi

# Create build directory
mkdir -p "$BUILD_DIR/$THEME_NAME"

# Copy theme files (exclude development/git files)
rsync -av --progress . "$BUILD_DIR/$THEME_NAME/" \
    --exclude='.git*' \
    --exclude='.DS_Store' \
    --exclude='build/' \
    --exclude='dist/' \
    --exclude='build.sh' \
    --exclude='who-is-chubes.md' \
    --exclude='CLAUDE.md' \
    --exclude='README.md' \
    --exclude='*.zip'

# Create zip file
cd "$BUILD_DIR"
zip -r "../$DIST_DIR/$ZIP_NAME" "$THEME_NAME/"
cd ..

# Copy theme directory to dist for FTP upload
cp -r "$BUILD_DIR/$THEME_NAME" "$DIST_DIR/"

# Clean up build directory
rm -rf "$BUILD_DIR"

echo "✅ Theme packaged successfully:"
echo "   📁 Directory: $DIST_DIR/$THEME_DIR (for FTP upload)"
echo "   📦 Zip file: $DIST_DIR/$ZIP_NAME (for WordPress admin)"
echo ""
echo "🚀 FTP Upload Instructions:"
echo "   Upload contents of $DIST_DIR/$THEME_DIR/ to /wp-content/themes/$THEME_NAME/"