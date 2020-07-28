# Social Auth 37Signals (Basecamp)

This project is part of the [Drupal Social Initiative](https://groups.drupal.org/social-initiative) and is based on the [Social API](https://www.drupal.org/project/social_api).

![](https://www.drupal.org/files/styles/grid-3-2x/public/project-images/basecamp_2019_logo.png?itok=KpMHkp2-)

Social Auth 37Signals allows users to register and login to your Drupal site with their 37Signals/Basecamp account. This module is based on Social Auth and Social API projects

This module adds a path `user/login/37signals` which redirects the user to 37Signals for authentication.

## Installation

You may have to edit the <i>root composer.json</i> to change the default library repository `nilesuan/oauth2-thirtysevensignals`, since the original repository is not updated for quite few years and the dependencies are not maintained.

```
"require": {
    "nilesuan/oauth2-thirtysevensignals": "dev-master",
    "drupal/social_auth": "^2.0"
},
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/oh-bala/oauth2-thirtysevensignals.git"
    }
]
```

## Dependencies

* [OAuth2 ThirtySevenSignals](https://github.com/oh-bala/oauth2-thirtysevensignals)

## Improvements

Currently, not getting user's avatar.
