> [!NOTE]
> This addon has been archived since impersonating users was added to Statamic core in [v4.23.0](https://github.com/statamic/cms/releases/tag/v4.23.0)

---

# Impersonator

**Give Admins the ability to authenticate as any user for easier debugging.**

## Installation

Install the addon via composer:

```
composer require aryehraber/statamic-impersonator
```

Publish the config file (optional):

```
php please vendor:publish --tag=impersonator-config
```

After installation, all Super Admins will see a new Impersonator utility listed under `Tools > Utilities`. Regular CP users can also be given access, but will require the "Impersonator" permission to be activated for their User Role.

## Usage

Simply navigate to `Tools > Utilities > Impersonator`, select the user that you want to authenticate as and click "Go". This will authenticate you as the selected user and automatically redirect you to the CP Dashboard, or the Homepage if the user doesn't have CP access.

You are now free to do what you need as this user to debug or give support regarding an issue.

Once finished, you can easily return back to your own account by clicking the "Back to my account" link:
  * **CP**: a link can be found in the sidebar nav.
  * **Frontend**: a small icon should appear in the bottom right corner (only if `inject_terminate_link` is set to `true`).

If either of these doesn't work as expected, you should manually log out and log back in as yourself.

### Tags

`{{ impersonator:active }}`

This tag allows you to check whether the current user is in an active Impersonation session, this can be useful to conditionally show/hide content.

**Examples:**

Show the terminate link to Impersonators only:

```antlers
{{ if {impersonator:active} }}
  <a href="{{ impersonator:terminate }}">Head Back</a>
{{ /if }}
```

Hide sensitive information from Impersonators:

```antlers
{{ unless {impersonator:active} }}
  <p>Personal user info</p>
{{ /unless }}
```

---

`{{ impersonator:terminate }}`

This tag outputs the action URL to terminate the Impersonation session, this can be useful if the `inject_terminate_link` config option has been set to `false`.

## Security

If you discover any security-related issues, please email aryeh.raber@gmail.com instead of using the issue tracker.
