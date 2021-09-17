# Html2Pdf

## Configuration


## Parameters

### Page Dimensions
| Parameter   | Description                                                                                                      |
| :---------- | :--------------------------------------------------------------------------------------------------------------- |
| page_size   | Page size, such as A4, Letter etc.                                                                               |
| page_size   | Page size, such as A4, Letter etc.                                                                               |
| orientation | Portrait or Landscape orientation                                                                                |
| width       | Width of the page in unit                                                                                        |
| height      | Height of the page in unit. If you specify a width, but not a height, then we generate one long single page PDF. |
| top         | Top margin in unit                                                                                               |
| bottom      | Bottom margin in unit                                                                                            |
| left        | Left margin in unit                                                                                              |
| right       | Right margin in unit                                                                                             |
| unit        | Measurement unit, which is applied to the margins as well as the width and height.                               |

### Scale Dimension
| Parameter     | Description                            |
| ------------- | -------------------------------------- |
| screen_width  | Width of the screen                    |
| screen_height | Height of the screen                   |
| zoom_factor   | Use this zoom factor during conversion |

### Conversion Parameters
| Parameter         | Description                                                                                                          |
| ----------------- | -------------------------------------------------------------------------------------------------------------------- |
| content           | Controls which content of the page you want to convert or exclude. See part of page conversion for more info.        |
| css               | Use custom CSS to style the page to your needs                                                                       |
| css_media_type    | Use print for the print friendly layout (CSS media type 'print') if your web page has one                            |
| bookmarks         | Convert the headers `<h1>` through `<h4>` to bookmarks in your PDF so you click through to those different sections. |
| grayscale         | Convert to a grayscale PDF                                                                                           |
| no_background     | Do not show the web page background in the PDF                                                                       |
| no_images         | Do not include any images from the web page in the PDF                                                               |
| no_external_links | Do not show hyperlinks to other domains in the PDF                                                                   |
| no_internal_links | Do not show hyperlinks within the domain in the PDF                                                                  |
| no_javascript     | Convert the page with JavaScript switched off                                                                        |
| javascript_time   | Wait this time in milliseconds for JavaScript to complete, default is 200                                            |
| title             | Set the title of the PDF instead of the value of the `<title>` tag from the web page                                 |
| filename          | Set the filename of the PDF, which you see in the top bar when you open the PDF in Adobe Reader.                     |
| toc               | Include a table of contents at the beginning of the PDF.                                                             |

### Header and Footer settings
| Parameter   | Description                                                                                                                                                                 |
| ----------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| header      | HTML that you want to use as header                                                                                                                                         |
| footer      | HTML that you want to use as footer                                                                                                                                         |
| page_offset | Number that will be added to the page number, by default this is 0 so page numbers will  start at 1.<br>Example: set this to -1 if you want the page numbers to start at 0. |

### Page background
| Parameter  | Description                                                                                                     |
| ---------- | --------------------------------------------------------------------------------------------------------------- |
| bg         | URL of the background image, used for the first page (and other pages if bg2 or bg3 are not specified)          |
| bg2        | URL of the background image, used from the second page onwards                                                  |
| bg3        | URL of the background image, used for the last page                                                             |
| bg_x       | The horizontal position for the background. Default is 0, which is the absolute left of the page                |
| bg_y       | The vertical position for the background. Default is 0, which is the absolute top of the page                   |
| bg_sx      | The horizontal scaling factor for the background. If you use 0 then we will scale to the full width of the page |
| bg_sy      | The vertical scaling factor for the background. If you use 0 then we will scale to the full height of the page  |
| bg_opacity | Opacity between 0 and 1. Default is 1, which is fully opague                                                    |
| bg_angle   | Angle of rotation between 0 and 360                                                                             |

### PDF encryption and rights management
| Parameter        | Description                                                         |
| ---------------- | ------------------------------------------------------------------- |
| encryption_level | Level of encryption of the PDF, which accepts the following values: |
|                  | - 40: 40-bit RC4                                                    |
|                  | - 128: 128-bit RC4                                                  |
|                  | - 128aes: 128-bit AES                                               |
|                  | - 256: 256-bit AES                                                  |
| user_password    | Password needed to open the PDF                                     |
| owner_password   | Password needed to adjust the rights management settings of the PDF |
| no_print         | Prevent users from printing the PDF                                 |
| no_copy          | Prevent users from copying content from the PDF                     |
| no_modify        | Prevent users from annotating (commenting) the PDF                  |

## Usage

- Usage: [POST] `https://myservicePDF`
- Header: Authorization Bearer token JWT

### POST paramaters
```
{
    "url": "https://www.google.com",
    "html": "<strong>PDF from html code inline</strong>",
    "options":{
        "pdf":{
            "title": "Html to Pdf Generator",
            "orientation": "Portrait",
            "header-center" : "From Html to Pdf Generator",
            "footer-center" : "from Wyzen"
        }
    }
}
```
