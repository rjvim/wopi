# Background

This is aimed towards https://wopi.readthedocs.io/ (Office for web)

As a beginner, WOPI implementation for Microsoft Office for web can seem daunting and clueless. It can sound very difficult as the starting point is not clear.

Let's understand few concepts:

1. The idea is to view/edit your files using Office, like a Word document.
2. So, you host your file whereever you want, like S3 but for editing purpose we use Office. Seems like a great option.

## Open a page to edit your document

If you want your user to edit a word doc from your application, an obivous thing would be you want to open a page with that document for viewing or editing. WOPI refers to this as "Host Page" - https://wopi.readthedocs.io/en/latest/hostpage.html

Host page is something we make! And we will provide place in that page so that Office can load necessary UI/Functionalities for Editing that document. Seems Fair! And for this Office expects a webpage with an iframe. And that iframe id should be "office_frame", again, a fair expectation.

But how would Office even know that you open a page and you are expecting Office to load its functionality in your page. Simple, you make a simple form and "Submit it".

If you ever, made a form, you need an "action" attribute. What should be the value of that action attribute. Aha! Now we are getting into the crux, that's what discovery url is for!

You can find these at: https://wopi.readthedocs.io/en/latest/build_test_ship/environments.html

At time of writing this, test url is: https://ffc-onenote.officeapps.live.com/hosting/discovery

Here you will find some XML (Might seem, what the hell!), but please stay on course.

You can observe the first level of `<app name="Excel"`,`<app name="Word"` etc.,

As we want to view a word doc to being with, let's go into `<app name="Word"` Let's say we want to view a word document with extension `.doc`

So, if we can try to guess something:

```
<action name="view" ext="doc" default="true" urlsrc="https://FFC-word-view.officeapps.live.com/wv/wordviewerframe.aspx?<ui=UI_LLCC&><rs=DC_LLCC&><dchat=DISABLE_CHAT&><hid=HOST_SESSION_ID&><sc=SESSION_CONTEXT&><wopisrc=WOPI_SOURCE&><showpagestats=PERFSTATS&><IsLicensedUser=BUSINESS_USER&><actnavid=ACTIVITY_NAVIGATION_ID&>"/>
```

The following line should give your some idea - action is "view" and ext is doc, Aha, something relevant.

Let's park this here for now, atleast we know the above xml has something to do with us viewing a word doc! Ok, what next?

Let's put together a host page, let's take from: https://github.com/Microsoft/Office-Online-Test-Tools-and-Documentation/blob/master/samples/SampleHostPage.html, which we changed and put in `resources/views/host.blade.php` with Laravel blade variables.

Off the bat, if you focus on discovery xml once more, u will find an attribute `favIconUrl` and our host page also has `$favIconUrl`, Atleast we know where this value is going to come from. And don't worry too much, at this point, all we need to figure out first is what would be value of `$officeActionUrl`

### officeActionUrl

From above XML, let's take urlsrc and wonder about it for a minute:

`https://FFC-word-view.officeapps.live.com/wv/wordviewerframe.aspx?<ui=UI_LLCC&><rs=DC_LLCC&><dchat=DISABLE_CHAT&><hid=HOST_SESSION_ID&><sc=SESSION_CONTEXT&><wopisrc=WOPI_SOURCE&><showpagestats=PERFSTATS&><IsLicensedUser=BUSINESS_USER&><actnavid=ACTIVITY_NAVIGATION_ID&>`

Few things which are distracting as hell are:

-   UI_LLCC
-   DC_LLCC
-   DISABLE_CHAT
-   HOST_SESSION_ID
-   SESSION_CONTEXT
-   WOPI_SOURCE
-   PERFSTATS
-   BUSINESS_USER
-   ACTIVITY_NAVIGATION_ID

Let's patiently get some idea about these, and the relevant documentation for this is: https://wopi.readthedocs.io/en/latest/discovery.html?#action-urls

An importance sentence: "A WOPI host must transform the URIs" - So we need to transform this urlsrc and supply it as action url!

Ok, and this "the host must parse and replace Placeholder values with appropriate values or discard them completely." -> Ok, getting some idea.

Now this "When the URL is opened, the action will be invoked against the file indicated by the WOPISrc."

Ok, so some unknown complexity, WOPISrc is the one to which we have to tell which file to process/open and whatever!

Let's go back to above values again, so the documentation for it here:

https://wopi.readthedocs.io/en/latest/discovery.html?#placeholder-values

-   UI_LLCC (optional, forget it then)
-   DC_LLCC (optional)
-   DISABLE_CHAT (optional, forget it)
-   HOST_SESSION_ID (optional)
-   SESSION_CONTEXT (optional)
-   WOPI_SOURCE
-   PERFSTATS (optional)
-   BUSINESS_USER = Can be set to 1, cool!
-   ACTIVITY_NAVIGATION_ID (optional)

Ok, we need to supply only - WOPI_SOURCE and our url becomes:

`https://FFC-word-view.officeapps.live.com/wv/wordviewerframe.aspx?<wopisrc=WOPI_SOURCE&>`, If we clean it up:

`https://FFC-word-view.officeapps.live.com/wv/wordviewerframe.aspx?wopisrc=$wopiSrc`

Documentation is here: https://wopi.readthedocs.io/projects/wopirest/en/latest/concepts.html#term-wopisrc

It gives an example for this: `https://wopi.contoso.com/wopi/files/abcdef0123456789`

Ok, it is the endpoint at which we host the file, and "abcdef0123456789" is the file-id! Now, we getting into the meat of the problem.

We need to add some endpoints to our app now! Without going into too much concept, the following are the apis which are must needed:

From here (https://wopi.readthedocs.io/en/latest/wopi_requirements.html#requirements), for "View" we need to implement two endpoints:

CheckFileInfo (https://wopi.readthedocs.io/projects/wopirest/en/latest/files/CheckFileInfo.html#checkfileinfo)

-   `GET /wopi/files/(file_id)`

And we should return a json response with following keys:

```
{
    "BaseFileName" : "",
    "OwnerId" : "",
    "Size" : "",
    "UserId" : "",
    "Version" : "",
}
```

Looks like we hardcode pretty much everything, except BaseFileName and Size

GetFile (https://wopi.readthedocs.io/projects/wopirest/en/latest/files/GetFile.html#getfile)

-   `GET /wopi/files/(file_id)/contents`
