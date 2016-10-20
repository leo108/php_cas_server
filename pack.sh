#!/usr/bin/env bash

rm -rf public/build public/css public/js build.zip
yarn
npm run prod
git clean -e public -e vendor -e '.env' -e '.idea' -dxf
zip -q -r build.zip . -x ".git/*" -x build.zip -x ".idea/*" -x ".env"
