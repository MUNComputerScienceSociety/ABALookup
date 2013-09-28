Sass
====

A bit of information about the contents of this folder.

Installing the Sass gem
-----------------------

    gem install sass # This may need to be run as root

Directory structure
-------------------

The main Sass file (`main.scss`) imports the other files as needed via `@import` rules. Partial Sass files begin with an underscore, and reside in the `paritals/` folder - these files do not generate a CSS counterpart. Highly-reusable classes, such as buttons, are in the `partials/components/` folder.

Vendor files are in the `vendor/` folder.

All Sass files are authored in the `.scss` syntax.

- `partials/`
    - `components/`
        - **`_buttons`**: The button styles
        - **`_colors`**: Simple style classes for single colors
    - `fonts/`
        - The `@font-face` rules for various typefaces
    - **`_all`**: A shortcut for importing all the files in the folder
    - **`_footer`**: The styles for the site footer
    - **`_forms`**: Form-related styles
    - **`_header`**: Styles for the site header, logo, and nav
    - **`_intro`**: Styles for the tagline on the homepage
    - **`_layout`**: Main content area and asides
    - **`_matches`**: Styles the matches
    - **`_mixins`**: General-use mixins (e.g. CSS triangles)
    - **`_reset`**: A barebones reset
    - **`_schedule`**: Styles for the user schedule
    - **`_typography`**: Base typography placeholders
    - **`_variables`**: Variable definitions
- `vendor/`
    - [A custom build of the ZURB foundation 3 framework](http://foundation.zurb.com/download-f3.php)
    - v1.1.2 of [Normalize.css](http://necolas.github.io/normalize.css/) (renamed with a `.scss` extension)
- **`main`**: The main Sass file

Generating CSS from Sass (compiling)
------------------------------------

- Run the `sass` Grunt task:

        grunt sass
