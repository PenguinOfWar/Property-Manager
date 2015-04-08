#Property Manager Plugin

Thanks to Alexey Bobkov and Samuel Georges who wrote the RainLab Blog plugin, I've learned a lot from their examples!

This plugin implements property management functionality (real estate, dealer locator etc) for the [OctoberCMS](http://octobercms.com).

## Configuring

If you're installing this plugin manually, simply copy the contents of this directory to:

/plugins/ctmh/propertymanager/

In order to use the plugin you need to get the API keys from your [Google Code account](https://code.google.com/apis/console).

You will need

1. Google Maps v3 API key
2. Google Geocoder API key

### Steps

1. In the OctoberCMS back-end go to the System / Settings page and click the Property Manager link. 
2. Paste the API keys in the relevant fields.

## State of development

The backend administrative features are implemented, but none of the backend components are available. As such, this plugin can not be used in anger, but feel free to have a look around.

The frontend components are currently in development and will be available as soon as possible.

## To-Do List

- Configurable local currency formatting
- Choose between km and miles

And more as I think of them and (hopefully) get some feedback.