# KnownReactions

Publish IndieWeb-style [likes](http://indiewebcamp.com/like) and [reposts](http://indiewebcamp.com/reposts) from Known.

![](http://i.giphy.com/VW9xuM3avNffW.gif)

The original Like plugin evolved to support Bookmarks instead (I
suspect because that's what most users want), so this plugin intends
to fill the gap.

## Installation

[Download](https://github.com/kylewm/KnownReactions/archive/master.zip) the [repository](https://github.com/kylewm/KnownReactions) and upload it to your server into a folder named <code>Reactions</code> within the <code>IdnoPlugins</code> folder your [Known](https://github.com/idno/Known) installation. Once uploaded, go to the plugins tab of the Site Configuration for your Known site (typically http://www.yoursitname.com/admin/plugins) and click "Enable" on the Reactions plugin. The "Like" and "Repost" icons and functionality should then be available in your posting interface. 

## License

This software is dedicated to the public domain under Creative Commons [CC0][].

[CC0]: http://creativecommons.org/publicdomain/zero/1.0/


## Changelog

- 0.1.4 - 2016-03-13: show title and description boxes
  even if there was an error fetching the source.
- 0.1.3 - 2016-02-09: add argument drawSyndication call
  to support new retroactive posse feature in core.
