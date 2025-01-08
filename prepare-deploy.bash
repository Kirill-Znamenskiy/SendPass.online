#!/bin/bash

set -xe

cd "$(dirname "$0")"

set +x

for FFF in favicon.ico apple-touch-icon.png apple-touch-icon-precomposed.png; do
    ln -svhF ./icons/$FFF ./public/$FFF
done

for EXT in svg png; do
    ln -svhF ./logos/logo-64x64.$EXT ./public/icon.$EXT
    ln -svhF ./logos/logo-inverted-64x64.$EXT ./public/icon-inverted.$EXT

    ln -svhF ./logos/logo-320x320.$EXT ./public/logo.$EXT
    ln -svhF ./logos/logo-64x64.$EXT ./public/logo-64.$EXT
    ln -svhF ./logos/logo-320x320.$EXT ./public/logo-320.$EXT

    ln -svhF ./logos/logo-inverted-320x320.$EXT ./public/logo-inverted.$EXT
    ln -svhF ./logos/logo-inverted-64x64.$EXT ./public/logo-inverted-64.$EXT
    ln -svhF ./logos/logo-inverted-320x320.$EXT ./public/logo-inverted-320.$EXT
done

npm run production



