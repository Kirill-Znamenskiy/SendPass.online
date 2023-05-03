#!/bin/bash



if test -f "./make.bash"; then
    cd ..
fi

if test ! -f "./make/make.bash"; then
    echo "Work directory '$PWD' is wrong!"
    exit
fi

if [ $# == 2 ] && [ "$2" == "--real" ]; then
    DRY_RUN=""
else
    DRY_RUN="--dry-run"
fi

deploy() {
    printf "\n\n"
    printf "=============== DEPLOY STARTED  AT $(/bin/date) ===============\n"

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


    set -xe

    npm run production

    rsync -av $DRY_RUN --delete \
        ./.env.PROD.env HEL:sites/SendPass.online/.env \
    ;

    rsync -av $DRY_RUN --delete \
        --exclude=".git" --exclude=".DS_Store" \
        --exclude="/runtime*" \
        --exclude="/storage*" \
        --exclude="/bootstrap/cache*" \
        --exclude="/.env*" \
        --exclude="/vendor*" \
        --exclude="/node_modules*" \
        --exclude="/composer.lock" \
        --exclude="/composer.phar" \
        ./ HEL:sites/SendPass.online/ \
    ;

    set +xe

    printf "=============== DEPLOY FINISHED AT $(/bin/date) ===============\n"
    printf "\n\n"
}

if [ -z "$1" ]; then
    echo "Empty parameters!"
elif [ "$1" == "deploy" ]; then
    deploy
else
    echo "Unknown parameter '$1'"
fi
