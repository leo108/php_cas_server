#!/usr/bin/env bash

rm -rf public/build public/css public/js
yarn
npm run prod
git clean -e public -e vendor -dxf
zip -r build.zip . -x ".git/*" -x build.zip