# wp_pdf.js

Publish PDF presentations and documents in your posts.

This repository is a fork of the plugin [wp-pdf.js](https://wordpress.org/plugins/wp-pdfjs) in the Wordpress plugin repository:

Original Contributors: hkropp
License: GPLv3 
License URI: http://www.gnu.org/licenses/gpl-3.0.txt

## Installation

- Download ZIP file from Github and unzip contents into your wp-contents/plugins/wp-pdfjs directory.
- Activate the plugin in the Wordpress backend.

## Usage

Use the include function.

Example usage:

    [wp_pdfjs id=189 scale=0.2]

Options:

* `id`: You can provide an id or url. If you provide an id, than it has to be the Wordpress ID of the document.
* `url`: You can provide an id or url. If you provide an url you have to make sure it is publicly accessible and a direct link to the document.
* `scale`: The scale of the document. Default is set to '1.2'.
* `download`: If a download link should appear or not.

## Changelog

0.2
* Download link of the document.
* Usage of GLYPHICONS icons for navigation http://glyphicons.com/glyphicons-licenses/ 
* Include documents by URL.

0.1.1
* Bugfix: Compatibility with s2Member plugin http://wordpress.org/support/topic/bug-report-11

0.1
* Initial release
