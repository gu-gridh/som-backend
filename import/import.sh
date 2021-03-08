#!/bin/bash

# Erase existing data and read it anew from import/data/*.tsv
# Usage:
#   import/import.sh
#   DB_USER=me import/import.sh

DB_USER=${DB_USER:-root}
DB_DATABASE=${DB_DATABASE:-som}
DIR=${DIR:-$(pwd)/$(dirname $0)/data}

# Read model definition (this will erase existing data).
QUERIES=$(cat $(dirname $0)/model.sql)

# Add a LOAD statement for each .tsv file.
for TSV in "$DIR"/*.tsv; do
    TABLE=$(basename $TSV .tsv)
    QUERIES="$QUERIES
        LOAD DATA INFILE '$DIR/$TABLE.tsv'
        INTO TABLE $TABLE
        FIELDS TERMINATED BY '\t' ENCLOSED BY ''
        LINES TERMINATED BY '\n';
        "
done

# Make all SQL queries in one step to avoid having to enter the password multiple times.
echo $QUERIES | mysql -u $DB_USER -p $DB_DATABASE
