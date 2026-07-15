const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch();
  const page = await browser.newPage();
  
  page.on('pageerror', (err) => {
    console.error('PAGE RUNTIME ERROR:', err.message);
    console.error(err.stack);
  });
  
  page.on('console', (msg) => {
    if (msg.type() === 'error') {
      console.error('CONSOLE ERROR:', msg.text());
    } else {
      console.log('CONSOLE LOG:', msg.text());
    }
  });

  try {
    console.log('Navigating to http://localhost:5173/ ...');
    await page.goto('http://localhost:5173/', { waitUntil: 'load', timeout: 5000 });
    // wait a moment for react mount
    await page.waitForTimeout(2000);
  } catch (err) {
    console.error('Navigation or timeout error:', err.message);
  } finally {
    await browser.close();
  }
})();
