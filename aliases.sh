#!/bin/bash

#set -euo pipefail

pushd . >/dev/null
	INIT_SOURCE=${BASH_SOURCE[0]}
	if [ -z "$INIT_SOURCE" ]; then
		INIT_SOURCE=$(echo .)
	fi

	BASEDIR="${INIT_SOURCE}"
	if [ -h "${BASEDIR}" ]; then
		while [ -h "${BASEDIR}" ]; do
			cd $(dirname "$BASEDIR")
			BASEDIR=$(readlink "${BASEDIR}")
		done
	fi
	cd $(dirname ${BASEDIR}) >/dev/null
	BASEDIR=$(pwd)
popd >/dev/null

makeAlias(){
  alias $1="$2"
  echo "$3"
}

##Aliases definition
makeAlias "phpunit" "${BASEDIR}/vendor/bin/phpunit" "Created alias 'phpunit' for default format"
#alias phpunit="${BASEDIR}/vendor/bin/phpunit"
#echo "Created alias 'phpunit' for default format"

makeAlias "punit" "phpunit --testdox" "Create alias 'punit' for testdox format"
#alias punit="phpunit --testdox"
#echo "Created alias 'punit' for testdox format"

makeAlias "phpunit_coverage" "phpunit --coverage-html coverage/" "Created alias 'phpunit_coverage' for code coverage"
#alias phpunit_coverage="phpunit --coverage-html coverage/"
#echo "Created alias 'phpunit_coverage' for code coverage"
