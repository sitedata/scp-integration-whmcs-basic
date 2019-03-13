#!/usr/bin/env bash

OUTFILE=/scp/install.synergycp.com/bm/integration/whmcs/synergycpbasic.zip
zip -r "$OUTFILE" . -x ".git*" ".idea/*" "bin/*"
