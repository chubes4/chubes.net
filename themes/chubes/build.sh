#!/bin/bash

# WordPress Theme Build Script
# Creates production zip file for WordPress theme installation in /build/

set -e

THEME_NAME="chubes"
BUILD_DIR="build"

echo "Building WordPress theme: $THEME_NAME"

rm -rf "$BUILD_DIR"
mkdir -p "$BUILD_DIR/$THEME_NAME"

rsync -av --progress . "$BUILD_DIR/$THEME_NAME/" \
    --exclude='.git*' \
    --exclude='.DS_Store' \
    --exclude='.claude/' \
    --exclude='build/' \
    --exclude='build.sh' \
    --exclude='dist/' \
    --exclude='docs/' \
    --exclude='CLAUDE.md' \
    --exclude='README.md' \
    --exclude='AGENTS.md' \
    --exclude='CLAUDE.md' \
    --exclude='*.zip' \
    --exclude='assets/fonts/*.ttf'

cd "$BUILD_DIR"
zip -r "${THEME_NAME}.zip" "$THEME_NAME/"
cd ..

rm -rf "$BUILD_DIR/$THEME_NAME"

echo "✅ Build complete: $BUILD_DIR/${THEME_NAME}.zip"