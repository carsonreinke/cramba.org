/*
 * Purge unused CSS by crawling site
 *
 * `env URL=[Site to crawl] FILE=[CSS file to purge on] node purgecss.js`
 */
const PurgeCSS = require('purgecss').default,
    purgecssWordpress = require('purgecss-with-wordpress'),
    fs = require('fs'),
    util = require('util'),
    Crawler = require("simplecrawler");

const URL = process.env.URL,
    FILE = process.env.FILE;
const OUTPUT = FILE.replace('.css', '.min.css');

/**
 * Gather all HTML content on site
 * 
 * @returns Promise
 */
function crawl() {
    return new Promise((resolve, reject) => {
        const contents = [];
        const crawler = new Crawler(URL);

        crawler.respectRobotsTxt = false;
        crawler.supportedMimeTypes = [/text\/html.*/];
        crawler.downloadUnsupported = false;

        crawler.on('fetchcomplete', (item, responseBuffer) => {
            console.log(`Found ${item.url}`);
            contents.push(responseBuffer.toString());
        });
        crawler.on('complete', () => {
            resolve(contents);
        });
        crawler.on('fetchclienterror', (_, error) => {
            crawler.stop();
            reject(error);
        });
        crawler.on('fetchdataerror', (_, error) => {
            crawler.stop();
            reject(error);
        });
        crawler.start();
    });
}

(async () => {
    try {
        console.log(`Crawling ${URL}`);
        const contents = await crawl();

        console.log(`Purging ${FILE}`);
        const files = await new PurgeCSS().purge({
            content: contents.map((content) => {
                return { raw: content, extension: 'html' }
            }),
            css: [FILE],
            whitelist: [
                ...purgecssWordpress.whitelist,
                'menu-toggle',
                'open'
            ],
            whitelistPatterns: purgecssWordpress.whitelistPatterns
        });

        console.log(`Writing ${OUTPUT}`)
        await util.promisify(fs.writeFile)(OUTPUT, files[0].css);
    }
    catch (err) {
        console.error(err);
    }
})();