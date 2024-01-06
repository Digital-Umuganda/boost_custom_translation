**Introduction**
=

This is a ***Moodle*** **theme** that inherits its main functionalities from the **Boost theme** and adds an additional bar that is located below the top navigation bar. The bar contains a right menu that contains a dropdown for languages and a popover/dropdown that explains what the dropdown is meant for. All of the draft implementations of the mockup use ***Bootstrap 4*** theming because it's what the default ***Moodle*** theme, which is the **Boost theme**, uses.

**Manual installation**
=
1. Clone this repository
2. Unzip it in the ``/theme`` directory of your Moodle installation
3. Be sure to install [``moodle-translate_courses``](https://github.com/Digital-Umuganda/moodle-translate_courses) plugin as well because some features of the theme depend on it
4. Also install the [``moodle-courseselect``](https://github.com/Digital-Umuganda/moodle-courseselect) custom field because it's also required

**Usage**
=
- To use it, go Moodle appearance settings and enable the theme
- The inline translation widget can be enabled by the user by using the toggle in the top navigation bar
- Translating the course
  - Follow the instruction from translation plugin(link)

***Using the translation feature for existing themes***

If you want to add the navigation bar and the inline translation widget to an existing theme, you need to add some files to your theme first. Those files are:

- ``layout/course.php``
- ``templates/course.mustache``

Copy those files to their respective folders and remember to change the ``layout/course.php``'s ``render_from_template`` function to reference your template's name.

After that, you have to set the course page to use our newly added course page template. Do it by copying the following into your theme's ``config`` file.

```php
$THEME->layouts = array(
    ....
    // Main course page.
    'course' => array(
        'file' => 'course.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
        'options' => array('langmenu' => true),
    ),
)
```

**Author**
=
- Developed by [Elvis Peace NDAHAYO RUGERO](https://github.com/nrep) and [Digital Umuganda](https://github.com/Digital-Umuganda) with the funding of [GIZ](https://www.giz.de) Fair Forward

**Todo**
=
- [ ] Add licence
- [x] Add links to required repos