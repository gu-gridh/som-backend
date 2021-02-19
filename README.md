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

8. Commit and push the resulting tsv files
9. Run `import/import.sh` on the server
   * Fix and commit any remaining inconsistencies in tsv data.
