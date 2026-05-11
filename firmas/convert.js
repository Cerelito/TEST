const { chromium } = require('/opt/node22/lib/node_modules/playwright');
const path = require('path');
const fs = require('fs');

const HTML_DIR = path.join(__dirname, 'html');
const JPG_DIR = path.join(__dirname, 'jpg');

async function convertHtmlToJpg(htmlFile) {
  const baseName = path.basename(htmlFile, '.html');
  const outFile = path.join(JPG_DIR, baseName + '.jpg');
  const fileUrl = 'file://' + path.resolve(htmlFile);

  const browser = await chromium.launch({
    executablePath: '/opt/pw-browsers/chromium-1194/chrome-linux/chrome',
    args: ['--no-sandbox', '--disable-setuid-sandbox']
  });

  const page = await browser.newPage();

  // Viewport slightly larger than the card so body padding is visible
  await page.setViewportSize({ width: 720, height: 300 });
  await page.goto(fileUrl, { waitUntil: 'networkidle' });

  // Get the exact bounding box of the signature card
  const card = await page.$('.signature-card');
  const box = await card.boundingBox();

  await page.screenshot({
    path: outFile,
    type: 'jpeg',
    quality: 95,
    clip: {
      x: box.x,
      y: box.y,
      width: box.width,
      height: box.height
    }
  });

  await browser.close();
  console.log(`✓  ${baseName}.jpg`);
}

(async () => {
  if (!fs.existsSync(JPG_DIR)) fs.mkdirSync(JPG_DIR, { recursive: true });

  const files = fs.readdirSync(HTML_DIR)
    .filter(f => f.endsWith('.html'))
    .map(f => path.join(HTML_DIR, f));

  for (const file of files) {
    await convertHtmlToJpg(file);
  }

  console.log('\nDone! JPGs saved to:', JPG_DIR);
})();
