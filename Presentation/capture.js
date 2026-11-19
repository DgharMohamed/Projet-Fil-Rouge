const fs = require('fs');
const path = require('path');

const cacheRoot = path.join(process.env.LOCALAPPDATA, 'npm-cache', '_npx');
const dirs = fs.readdirSync(cacheRoot);
let puppeteerPath = null;
for (const d of dirs) {
  const p = path.join(cacheRoot, d, 'node_modules', 'puppeteer-core');
  if (fs.existsSync(p)) { puppeteerPath = p; break; }
}
if (!puppeteerPath) throw new Error('puppeteer-core introuvable');
const puppeteer = require(puppeteerPath);

const CHROME = 'C:/Program Files/Google/Chrome/Application/chrome.exe';
const OUT = path.join(__dirname, 'images');

(async () => {
  const browser = await puppeteer.launch({
    executablePath: CHROME,
    headless: 'new',
    args: ['--no-sandbox', '--disable-gpu', '--hide-scrollbars'],
  });
  const page = await browser.newPage();
  await page.setViewport({ width: 1280, height: 820, deviceScaleFactor: 2 });
  await page.goto('http://localhost:8000', { waitUntil: 'networkidle0' });
  await new Promise((r) => setTimeout(r, 1500));

  await page.screenshot({ path: path.join(OUT, 'ui_offres.png') });

  // Onglet Domaines
  await page.evaluate(() => {
    document.querySelector('[data-tab="domaines"]').click();
  });
  await new Promise((r) => setTimeout(r, 900));
  await page.screenshot({ path: path.join(OUT, 'ui_domaines.png') });

  // Modale d'ajout d'une offre
  await page.evaluate(() => {
    document.querySelector('[data-tab="offres"]').click();
  });
  await new Promise((r) => setTimeout(r, 600));
  await page.evaluate(() => {
    document.querySelector('#btnAddTop').click();
  });
  await new Promise((r) => setTimeout(r, 900));
  await page.screenshot({ path: path.join(OUT, 'ui_modale.png') });

  await browser.close();
  console.log('captures OK');
})();
