# som-backend

Backend for Somali speech corpus web app.

## Import data from FileMaker

1. Open Database_SomPro.fmp12 in FileMaker Pro
2. Choose the _Types_ table and click _Show All_
3. Choose File → Export records...
4. Choose _Tab-separated file_ and save as _Types.tab_
5. Choose fields indicated by `db/model.sql` in the same order
6. Do the same with the other tables (all of them?)
7. Remove/convert weird characters:

       for file in ../*.tab; do tr "\r" "\n" < $file | tr -d "\0\013" > import/data/$(basename $file .tab).tsv; done

8. Sensitive names in the data can be substituted with:

       cat import/data/Tokens.tsv | sed s/NAME1/Mf/g | [...] > import/data/Tokens2.tsv
       # Check results...

9. Review, commit and push the resulting tsv files
10. Run `import/import.sh`
   * Global FILE privilege is needed, which may be unsuitable for a server. Then do import locally and export/import database dump.
   * Fix and commit any remaining inconsistencies in tsv data.

## Serving the API

Create a `config.ini` file in this directory, containing at least the database host and password:

    DB_HOST=<host>
    DB_PASS=<password>

Serve the `db/` directory on a host with PHP and MySQL support. Do *not* allow access to `config.ini`.

For development, you can use PHP's built-in web server:

    php -S localhost:8030 -t db
