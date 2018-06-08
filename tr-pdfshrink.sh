#!/bin/bash
#tr-pdfshrink commandline to shrink pdf files by compressing images & document to 96dpi

temp=${1,,}
if test ! "${temp##*.}" = "pdf" #test - check file types and compare values
then
        echo "The file extension must be .pdf"
        exit
fi
gs -sDEVICE=pdfwrite \
-dDownsampleColorImages=true \
-dDownsampleGrayImages=true \
-dDownsampleMonoImages=true \
-dColorImageResolution=96 \
-dGrayImageResolution=96 \
-dMonoImageResolution=96 \
-dColorImageDownsampleThreshold=1.0 \
-dGrayImageDownsampleThreshold=1.0 \
-dMonoImageDownsampleThreshold=1.0 \
-dCompatibilityLevel=1.4 \
-dPDFSETTINGS=/ebook \
-dNOPAUSE \
-dQUIET \
-dBATCH \
-sOutputFile=${1%.*}_small.pdf ${1}

echo "The file " ${1} " was converted."
