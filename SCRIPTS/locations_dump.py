import sqlite3

DATABASE_FILENAME = "where the test SQlite database is"
LIST_OF_LOCATIONS = "../COPY/locations.txt"

sql = sqlite3.connect(DATABASE_FILENAME)

[sql.execute("INSERT INTO location VALUES(?, ?, ?)", (rid + 1, location, True, )) for rid, location in enumerate([line.strip() for line in open(LIST_OF_LOCATIONS).readlines()])]

sql.commit()
