// 🌐 網站測試工具 - Cloudflare Worker 單文件版本
// 直接複製此文件內容到 Cloudflare Workers 編輯器中即可使用

const HTML_CONTENT = `<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌐 網站測試工具</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; background: white; border-radius: 15px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); overflow: hidden; }
        .header { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 30px; text-align: center; }
        .header h1 { font-size: 2.2em; margin-bottom: 10px; }
        .header p { font-size: 1em; opacity: 0.9; }
        .content { padding: 30px; }
        .section { margin-bottom: 30px; }
        .section h2 { color: #333; margin-bottom: 15px; font-size: 1.3em; border-bottom: 2px solid #4facfe; padding-bottom: 8px; }
        .test-area { background: #f8f9fa; padding: 20px; border-radius: 10px; border: 2px solid #e9ecef; }
        .input-group { display: flex; gap: 10px; margin-bottom: 15px; }
        .url-input { flex: 1; padding: 12px; border: 2px solid #ddd; border-radius: 8px; font-size: 1em; }
        .url-input:focus { outline: none; border-color: #4facfe; }
        .btn { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border: none; padding: 12px 20px; border-radius: 8px; cursor: pointer; font-size: 1em; transition: all 0.3s; }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        .btn:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
        .results { margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #4facfe; display: none; }
        .result-item { margin-bottom: 10px; padding: 12px; background: white; border-radius: 6px; display: flex; justify-content: space-between; align-items: center; }
        .result-url { font-weight: bold; color: #333; flex: 1; margin-right: 10px; word-break: break-all; }
        .result-status { padding: 4px 8px; border-radius: 15px; font-size: 0.85em; font-weight: bold; }
        .status-success { background: #d4edda; color: #155724; }
        .status-error { background: #f8d7da; color: #721c24; }
        .status-loading { background: #fff3cd; color: #856404; }
        .loading { display: inline-block; width: 16px; height: 16px; border: 2px solid #f3f3f3; border-top: 2px solid #4facfe; border-radius: 50%; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .footer { text-align: center; padding: 15px; color: #666; border-top: 1px solid #eee; font-size: 0.9em; }
        .speed-dashboard { background: #2c3e50; color: white; padding: 25px; border-radius: 10px; text-align: center; margin-top: 15px; display: none; }
        .speed-metrics { display: flex; justify-content: center; gap: 30px; margin: 20px 0; flex-wrap: wrap; }
        .metric { text-align: center; min-width: 100px; }
        .metric-label { color: #95a5a6; font-size: 0.9em; margin-bottom: 8px; }
        .metric-value { font-size: 2em; font-weight: bold; line-height: 1; }
        .metric-unit { color: #95a5a6; font-size: 0.9em; margin-top: 3px; }
        .progress-bar { margin-top: 15px; height: 3px; background: #34495e; border-radius: 2px; overflow: hidden; }
        .progress-fill { height: 100%; background: #1abc9c; width: 0%; transition: width 0.3s; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🌐 網站測試工具</h1>
            <p>快速測試網站連通性和響應時間</p>
        </div>
        <div class="content">
            <div class="section">
                <h2>🔧 自定義網址測試</h2>
                <div class="test-area">
                    <div class="input-group">
                        <input type="url" class="url-input" id="urlInput" placeholder="輸入網址，如：https://www.google.com" onkeypress="if(event.key==='Enter')testUrl()">
                        <button class="btn" onclick="testUrl()">測試</button>
                    </div>
                    <div class="results" id="singleResult"><div id="singleList"></div></div>
                </div>
            </div>
            <div class="section">
                <h2>🎯 批量測試</h2>
                <div class="test-area">
                    <button class="btn" onclick="testBatch()" style="width:100%" id="batchBtn">測試主流網站</button>
                    <div class="results" id="batchResult"><div id="batchList"></div></div>
                </div>
            </div>
            <div class="section">
                <h2>🚀 網絡測速</h2>
                <div class="test-area">
                    <button class="btn" onclick="speedTest()" style="width:100%" id="speedBtn">開始測速</button>
                    <div class="speed-dashboard" id="speedDash">
                        <div id="speedStatus">準備測試...</div>
                        <div class="speed-metrics">
                            <div class="metric"><div class="metric-label">⬇ 下載</div><div id="download" class="metric-value" style="color:#1abc9c">0</div><div class="metric-unit">Mbps</div></div>
                            <div class="metric"><div class="metric-label">📡 延遲</div><div id="ping" class="metric-value" style="color:#f39c12">—</div><div class="metric-unit">ms</div></div>
                        </div>
                        <div class="progress-bar"><div id="progress" class="progress-fill"></div></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer"><p id="ipInfo">正在獲取IP...</p></div>
    </div>
    <script>
        const sites = ['https://www.google.com','https://www.github.com','https://www.youtube.com','https://www.baidu.com','https://chatgpt.com'];
        let testing = false;

        async function testUrl() {
            const url = document.getElementById('urlInput').value.trim();
            if (!url) return alert('請輸入網址');
            const testUrl = url.startsWith('http') ? url : 'https://' + url;

            document.getElementById('singleResult').style.display = 'block';
            document.getElementById('singleList').innerHTML = '';
            addResult(testUrl, '測試中...', 'loading', 'single');

            try {
                const res = await fetch('/api/test', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({url: testUrl})
                });
                const data = await res.json();
                updateResult(testUrl, data.success ? '成功 (' + data.responseTime + 'ms)' : '失敗 (' + data.responseTime + 'ms)', data.success ? 'success' : 'error', 'single');
            } catch (e) {
                updateResult(testUrl, '測試失敗', 'error', 'single');
            }
        }

        async function testBatch() {
            if (testing) return;
            testing = true;
            const btn = document.getElementById('batchBtn');
            btn.disabled = true;
            btn.textContent = '測試中...';

            document.getElementById('batchResult').style.display = 'block';
            document.getElementById('batchList').innerHTML = '';

            for (const url of sites) {
                addResult(url, '測試中...', 'loading', 'batch');
                try {
                    const res = await fetch('/api/test', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({url})
                    });
                    const data = await res.json();
                    updateResult(url, data.success ? '成功 (' + data.responseTime + 'ms)' : '失敗 (' + data.responseTime + 'ms)', data.success ? 'success' : 'error', 'batch');
                } catch (e) {
                    updateResult(url, '測試失敗', 'error', 'batch');
                }
            }

            btn.disabled = false;
            btn.textContent = '測試主流網站';
            testing = false;
        }

        async function speedTest() {
            const btn = document.getElementById('speedBtn');
            const dash = document.getElementById('speedDash');
            if (testing) return;
            testing = true;
            btn.disabled = true;
            btn.textContent = '測試中...';
            dash.style.display = 'block';

            document.getElementById('speedStatus').textContent = '測試延遲...';
            document.getElementById('progress').style.width = '20%';

            // 測試延遲
            const start = performance.now();
            try {
                await fetch('https://www.google.com/generate_204', {method: 'HEAD', mode: 'no-cors'});
                const ping = Math.round(performance.now() - start);
                document.getElementById('ping').textContent = ping;
            } catch (e) {
                document.getElementById('ping').textContent = '999';
            }

            document.getElementById('speedStatus').textContent = '測試下載速度...';
            document.getElementById('progress').style.width = '60%';

            // 測試下載速度
            const testStart = Date.now();
            let totalBytes = 0;
            try {
                const res = await fetch('https://httpbin.org/bytes/5242880');
                const reader = res.body.getReader();
                while (true) {
                    const {done, value} = await reader.read();
                    if (done) break;
                    totalBytes += value.length;
                }
                const elapsed = (Date.now() - testStart) / 1000;
                const speed = (totalBytes * 8) / elapsed / 1000000;
                document.getElementById('download').textContent = speed.toFixed(1);
            } catch (e) {
                document.getElementById('download').textContent = '0';
            }

            document.getElementById('speedStatus').textContent = '測試完成！';
            document.getElementById('progress').style.width = '100%';

            btn.disabled = false;
            btn.textContent = '開始測速';
            testing = false;
        }

        function addResult(url, status, type, container) {
            const list = document.getElementById(container + 'List');
            const item = document.createElement('div');
            item.className = 'result-item';
            item.id = 'result-' + container + '-' + btoa(url).replace(/[^a-zA-Z0-9]/g, '');

            let icon = type === 'loading' ? '<div class="loading"></div>' : (type === 'success' ? '✅' : '❌');
            let cls = 'status-' + type;

            item.innerHTML = '<div class="result-url">' + url + '</div><span class="result-status ' + cls + '">' + icon + ' ' + status + '</span>';
            list.appendChild(item);
        }

        function updateResult(url, status, type, container) {
            const item = document.getElementById('result-' + container + '-' + btoa(url).replace(/[^a-zA-Z0-9]/g, ''));
            if (item) {
                const statusEl = item.querySelector('.result-status');
                statusEl.className = 'result-status status-' + type;
                statusEl.innerHTML = (type === 'success' ? '✅' : '❌') + ' ' + status;
            }
        }

        async function getIP() {
            try {
                const res = await fetch('/api/ip');
                const data = await res.json();
                document.getElementById('ipInfo').textContent = '🌍 IP: ' + data.ip + ' | 📍 ' + data.city + ', ' + data.country;
            } catch (e) {
                document.getElementById('ipInfo').textContent = '❌ 無法獲取IP';
            }
        }

        document.addEventListener('DOMContentLoaded', getIP);
    </script>
</body>
</html>`;

export default {
  async fetch(request) {
    const url = new URL(request.url);

    // CORS 頭部
    const corsHeaders = {
      'Access-Control-Allow-Origin': '*',
      'Access-Control-Allow-Methods': 'GET, POST, OPTIONS',
      'Access-Control-Allow-Headers': 'Content-Type',
    };

    if (request.method === 'OPTIONS') {
      return new Response(null, { headers: corsHeaders });
    }

    // 主頁面
    if (url.pathname === '/') {
      return new Response(HTML_CONTENT, {
        headers: { 'Content-Type': 'text/html; charset=utf-8', ...corsHeaders }
      });
    }

    // API 路由
    if (url.pathname === '/api/test') {
      return handleTest(request, corsHeaders);
    }

    if (url.pathname === '/api/ip') {
      return handleIP(request, corsHeaders);
    }

    return new Response('Not Found', { status: 404, headers: corsHeaders });
  }
};

// API 處理函數
async function handleTest(request, corsHeaders) {
  try {
    const { url: testUrl } = await request.json();
    if (!testUrl) {
      return new Response(JSON.stringify({ error: 'URL required' }), {
        status: 400, headers: { 'Content-Type': 'application/json', ...corsHeaders }
      });
    }

    const startTime = Date.now();
    try {
      const controller = new AbortController();
      setTimeout(() => controller.abort(), 10000);

      const response = await fetch(testUrl, { method: 'HEAD', signal: controller.signal });
      const responseTime = Date.now() - startTime;

      return new Response(JSON.stringify({
        success: true, url: testUrl, responseTime, status: response.status
      }), { headers: { 'Content-Type': 'application/json', ...corsHeaders } });

    } catch (error) {
      const responseTime = Date.now() - startTime;
      return new Response(JSON.stringify({
        success: false, url: testUrl, responseTime, error: error.message
      }), { headers: { 'Content-Type': 'application/json', ...corsHeaders } });
    }
  } catch (error) {
    return new Response(JSON.stringify({ error: 'Invalid request' }), {
      status: 400, headers: { 'Content-Type': 'application/json', ...corsHeaders }
    });
  }
}

async function handleIP(request, corsHeaders) {
  const ip = request.headers.get('CF-Connecting-IP') || 'Unknown';
  const country = request.cf?.country || 'Unknown';
  const city = request.cf?.city || 'Unknown';

  return new Response(JSON.stringify({ ip, country, city }), {
    headers: { 'Content-Type': 'application/json', ...corsHeaders }
  });
}
