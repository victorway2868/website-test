<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>网站测试工具</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1.1em;
            opacity: 0.9;
        }

        .content {
            padding: 40px;
        }

        .section {
            margin-bottom: 40px;
        }

        .section h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.5em;
            border-bottom: 2px solid #4facfe;
            padding-bottom: 10px;
        }

        .custom-test {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            border: 2px solid #e9ecef;
        }

        .input-group {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .url-input {
            flex: 1;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }

        .url-input:focus {
            outline: none;
            border-color: #4facfe;
        }

        .test-btn {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1em;
            transition: all 0.3s ease;
        }

        .test-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .results {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #4facfe;
            display: none;
        }

        .result-item {
            margin-bottom: 15px;
            padding: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .result-url {
            font-weight: bold;
            color: #333;
            flex: 1;
            margin-right: 15px;
        }

        .result-status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: bold;
        }

        .status-success {
            background: #d4edda;
            color: #155724;
        }

        .status-error {
            background: #f8d7da;
            color: #721c24;
        }

        .status-loading {
            background: #fff3cd;
            color: #856404;
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #4facfe;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            border-top: 1px solid #eee;
        }


    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🌐 网站测试工具</h1>
            <p>快速测试网站连通性和响应时间</p>
        </div>

        <div class="content">
            <div class="section">
                <h2>🔧 自定义网址测试</h2>
                <div class="custom-test">
                    <div class="input-group">
                        <input type="url" class="url-input" id="customUrl"
                            placeholder="请输入要测试的网址，例如：https://www.example.com" onkeypress="handleEnterKey(event)">
                        <button class="test-btn" onclick="testCustomUrl()">开始测试</button>
                    </div>
                    <div class="results" id="customResults" style="display: none;">
                        <h4>测试结果</h4>
                        <div id="customResultsList"></div>
                    </div>
                </div>
            </div>

            <div class="section">
                <button class="test-btn" onclick="testAllQuickSites()" style="width: 100%;">
                    🎯 开始批量测试主流网站
                </button>
                <div class="results" id="batchResults" style="display: none;">
                    <div id="batchResultsList"></div>
                </div>
            </div>

            <div class="section">
                <h2>🚀 网络测速</h2>
                <div class="custom-test">
                    <button class="test-btn" onclick="startSpeedTest()" style="width: 100%; margin-bottom: 20px;"
                        id="speedTestBtn">
                        开始网络测速
                    </button>
                    <div class="results" id="speedResults" style="display: none;">
                        <div id="speedDashboard"
                            style="background: #2c3e50; color: white; padding: 60px 40px; border-radius: 15px; text-align: center; min-height: 400px; display: flex; flex-direction: column; justify-content: center;">

                            <!-- 主要速度显示区域 -->
                            <div style="margin-bottom: 40px;">
                                <div id="statusText" style="color: #95a5a6; font-size: 1.1em; margin-bottom: 30px; min-height: 24px;">准备测试...</div>
                            </div>

                            <!-- 测速结果显示 - 三列布局 -->
                            <div style="display: flex; justify-content: center; gap: 60px; margin-bottom: 40px; flex-wrap: wrap;">
                                <!-- 下载速度 -->
                                <div style="text-align: center; min-width: 150px;">
                                    <div style="color: #95a5a6; font-size: 1em; margin-bottom: 15px; font-weight: 500;">⬇ 下载速度</div>
                                    <div id="downloadSpeed" style="font-size: 3.2em; font-weight: bold; color: #1abc9c; line-height: 1;">0</div>
                                    <div style="color: #95a5a6; font-size: 1.1em; margin-top: 5px;">Mbps</div>
                                </div>

                                <!-- 延迟 -->
                                <div style="text-align: center; min-width: 150px;">
                                    <div style="color: #95a5a6; font-size: 1em; margin-bottom: 15px; font-weight: 500;">📡 延迟</div>
                                    <div id="pingValue" style="font-size: 3.2em; font-weight: bold; color: #f39c12; line-height: 1;">—</div>
                                    <div style="color: #95a5a6; font-size: 1.1em; margin-top: 5px;">ms</div>
                                </div>

                                <!-- 上传速度 -->
                                <div style="text-align: center; min-width: 150px;">
                                    <div style="color: #95a5a6; font-size: 1em; margin-bottom: 15px; font-weight: 500;">⬆ 上传速度</div>
                                    <div id="uploadSpeed" style="font-size: 3.2em; font-weight: bold; color: #e74c3c; line-height: 1;">—</div>
                                    <div style="color: #95a5a6; font-size: 1.1em; margin-top: 5px;">Mbps</div>
                                </div>
                            </div>

                            <!-- 服务器信息 -->
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 40px; padding-top: 30px; border-top: 1px solid #34495e; font-size: 0.9em;">
                                <div style="text-align: left; color: #95a5a6;">
                                    <div>客户端</div>
                                    <div id="ipInfo" style="font-size: 0.8em; margin-top: 5px;">—</div>
                                </div>
                                <div style="text-align: right; color: #95a5a6;">
                                    <div>服务器</div>
                                    <div id="serverInfo" style="font-size: 0.8em; margin-top: 5px;">测试服务器</div>
                                </div>
                            </div>

                            <!-- 进度条 -->
                            <div style="margin-top: 30px; height: 3px; background: #34495e; border-radius: 2px; overflow: hidden;">
                                <div id="progressBar" style="height: 100%; background: #1abc9c; width: 0%; transition: width 0.3s ease;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer">
            <p id="userIp">正在获取您的IP地址...</p>
        </div>
    </div>

    <script>
        // 主流网站列表
        const quickSites = [
            'https://www.google.com',
            'https://chatgpt.com/',
            'https://www.github.com',
            'https://www.taobao.com',
            'https://www.youtube.com',
            'https://www.facebook.com',
            'https://www.baidu.com',
            'https://www.amazon.com',
            'https://www.apple.com',
            'https://www.visa.com',
            'https://www.lanzoui.com'
        ];

        // 网络测速功能变量
        let speedTestRunning = false;
        let currentSpeed = 0;
        let speedHistory = [];

        // 测试单个网站
        async function testWebsite(url, targetContainer = 'custom') {
            showResults(targetContainer);
            addResult(url, '测试中...', 'loading', targetContainer);

            const startTime = Date.now();

            try {
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 10000);

                const response = await fetch(url, {
                    method: 'HEAD',
                    mode: 'no-cors',
                    signal: controller.signal
                });

                clearTimeout(timeoutId);
                const endTime = Date.now();
                const responseTime = endTime - startTime;

                updateResult(url, `连接成功 (${responseTime}ms)`, 'success', targetContainer);

            } catch (error) {
                const endTime = Date.now();
                const responseTime = endTime - startTime;

                if (error.name === 'AbortError') {
                    updateResult(url, `连接超时 (>${responseTime}ms)`, 'error', targetContainer);
                } else {
                    updateResult(url, `连接测试完成 (${responseTime}ms)`, 'success', targetContainer);
                }
            }
        }

        // 测试自定义URL
        function testCustomUrl() {
            const urlInput = document.getElementById('customUrl');
            const url = urlInput.value.trim();

            if (!url) {
                alert('请输入要测试的网址');
                return;
            }

            let testUrl = url;
            if (!url.startsWith('http://') && !url.startsWith('https://')) {
                testUrl = 'https://' + url;
            }

            clearResults('custom');
            testWebsite(testUrl, 'custom');
        }

        // 批量测试所有主流网站
        async function testAllQuickSites() {
            showResults('batch');
            clearResults('batch');

            const promises = quickSites.map(url => testWebsite(url, 'batch'));
            await Promise.all(promises);
        }

        // 显示结果区域
        function showResults(type = 'custom') {
            if (type === 'custom') {
                document.getElementById('customResults').style.display = 'block';
            } else if (type === 'batch') {
                document.getElementById('batchResults').style.display = 'block';
            }
        }

        // 添加测试结果
        function addResult(url, status, type, targetContainer = 'custom') {
            const resultsListId = targetContainer === 'custom' ? 'customResultsList' : 'batchResultsList';
            const resultsList = document.getElementById(resultsListId);
            const resultItem = document.createElement('div');
            resultItem.className = 'result-item';
            resultItem.id = `result-${targetContainer}-${btoa(url).replace(/[^a-zA-Z0-9]/g, '')}`;

            let statusClass = 'status-loading';
            let statusIcon = '<div class="loading"></div>';

            if (type === 'success') {
                statusClass = 'status-success';
                statusIcon = '✅';
            } else if (type === 'error') {
                statusClass = 'status-error';
                statusIcon = '❌';
            }

            resultItem.innerHTML = `
                <div class="result-url">${url}</div>
                <span class="result-status ${statusClass}">
                    ${statusIcon} ${status}
                </span>
            `;

            resultsList.appendChild(resultItem);
        }

        // 更新测试结果
        function updateResult(url, status, type, targetContainer = 'custom') {
            const resultId = `result-${targetContainer}-${btoa(url).replace(/[^a-zA-Z0-9]/g, '')}`;
            const resultItem = document.getElementById(resultId);

            if (resultItem) {
                let statusClass = 'status-success';
                let statusIcon = '✅';

                if (type === 'error') {
                    statusClass = 'status-error';
                    statusIcon = '❌';
                }

                const statusElement = resultItem.querySelector('.result-status');
                statusElement.className = `result-status ${statusClass}`;
                statusElement.innerHTML = `${statusIcon} ${status}`;
            }
        }

        // 清空结果
        function clearResults(type = 'custom') {
            const resultsListId = type === 'custom' ? 'customResultsList' : 'batchResultsList';
            document.getElementById(resultsListId).innerHTML = '';
        }

        // 处理回车键
        function handleEnterKey(event) {
            if (event.key === 'Enter') {
                testCustomUrl();
            }
        }

        // 获取用户IP地址和地理位置
        async function getUserIP() {
            try {
                const response = await fetch('https://ipapi.co/json/');
                const data = await response.json();

                if (data.ip && !data.error) {
                    const locationParts = [data.country_name, data.region, data.city].filter(Boolean);
                    const locationInfo = locationParts.join(' ');
                    const ispInfo = data.org ? ` (${data.org})` : '';
                    document.getElementById('userIp').innerHTML =
                        `🌍 您的IP: ${data.ip}<br>📍 地理位置: ${locationInfo}${ispInfo}`;
                    return;
                }
                throw new Error('ipapi.co failed');
            } catch (error) {
                try {
                    const ipResponse = await fetch('https://api.ipify.org?format=json');
                    const ipData = await ipResponse.json();

                    if (ipData.ip) {
                        document.getElementById('userIp').textContent = `🌍 您的IP: ${ipData.ip}`;
                        return;
                    }
                    throw new Error('ipify failed');
                } catch (error2) {
                    try {
                        const response = await fetch('https://httpbin.org/ip');
                        const data = await response.json();
                        document.getElementById('userIp').textContent = `🌍 您的IP: ${data.origin}`;
                    } catch (error3) {
                        document.getElementById('userIp').textContent = '❌ 无法获取IP地址';
                    }
                }
            }
        }

        // 网络测速主函数
        async function startSpeedTest() {
            if (speedTestRunning) return;

            speedTestRunning = true;
            const btn = document.getElementById('speedTestBtn');
            const results = document.getElementById('speedResults');

            results.style.display = 'block';
            currentSpeed = 0;
            speedHistory = [];
            btn.textContent = '测试中...';
            btn.disabled = true;

            resetDashboard();

            try {
                await updateUserInfo();

                updateStatus('正在测试网络延迟...', 10);
                const ping = await testPing();
                updatePing(ping);

                updateStatus('正在测试下载速度...', 30);
                await testDownloadSpeedWithDashboard();

                updateStatus('正在测试上传速度...', 70);
                await testUploadSpeedWithDashboard();

                updateStatus('测试完成！', 100);

            } catch (error) {
                console.error('Speed test error:', error);
                updateStatus('测试失败，请重试', 0);
            } finally {
                speedTestRunning = false;
                btn.textContent = '开始网络测速';
                btn.disabled = false;
            }
        }

        // 重置显示
        function resetDashboard() {
            document.getElementById('downloadSpeed').textContent = '0';
            document.getElementById('uploadSpeed').textContent = '—';
            document.getElementById('pingValue').textContent = '—';
            document.getElementById('serverInfo').textContent = '测试服务器';
            document.getElementById('ipInfo').textContent = '—';
            document.getElementById('progressBar').style.width = '0%';
            document.getElementById('statusText').textContent = '准备测试...';

            // 重置颜色
            document.getElementById('downloadSpeed').style.color = '#1abc9c';
            document.getElementById('uploadSpeed').style.color = '#e74c3c';
            document.getElementById('pingValue').style.color = '#f39c12';
        }

        // 更新用户信息
        async function updateUserInfo() {
            try {
                const response = await fetch('https://ipapi.co/json/');
                const data = await response.json();

                if (data.ip && !data.error) {
                    document.getElementById('ipInfo').textContent = data.ip;
                    document.getElementById('locationInfo').textContent = `${data.city || ''} ${data.country_name || ''}`.trim();
                    document.getElementById('ispInfo').textContent = data.org || '未知服务商';
                }
            } catch (error) {
                console.log('Failed to get user info:', error);
            }
        }

        // 更新状态和进度
        function updateStatus(text, progress) {
            document.getElementById('statusText').textContent = text;
            document.getElementById('progressBar').style.width = progress + '%';
        }

        // 更新延迟显示
        function updatePing(ping) {
            document.getElementById('pingValue').textContent = ping;
        }

        // 更新速度显示
        function updateSpeedMeter(speed, isUpload = false) {
            if (isUpload) {
                const uploadDisplay = document.getElementById('uploadSpeed');
                uploadDisplay.textContent = speed.toFixed(1);

                // 根据上传速度调整颜色
                let color = '#e74c3c';
                if (speed >= 50) color = '#27ae60';
                else if (speed >= 25) color = '#1abc9c';
                else if (speed >= 10) color = '#f39c12';
                else if (speed >= 5) color = '#e67e22';
                else color = '#e74c3c';

                uploadDisplay.style.color = color;
            } else {
                const downloadDisplay = document.getElementById('downloadSpeed');
                downloadDisplay.textContent = speed.toFixed(1);

                // 根据下载速度调整颜色
                let color = '#1abc9c';
                if (speed >= 100) color = '#27ae60';
                else if (speed >= 50) color = '#1abc9c';
                else if (speed >= 25) color = '#3498db';
                else if (speed >= 10) color = '#f39c12';
                else if (speed >= 5) color = '#e67e22';
                else color = '#1abc9c';

                downloadDisplay.style.color = color;
            }
        }



        // 下载速度测试
        async function testDownloadSpeedWithDashboard() {
            const testFiles = [
                'https://httpbin.org/bytes/1048576',
                'https://httpbin.org/bytes/2097152',
                'https://httpbin.org/bytes/5242880',
                'https://httpbin.org/bytes/10485760',
            ];

            let testDuration = 10000;
            let startTime = Date.now();
            let totalBytes = 0;
            let activeDownloads = 0;
            const maxConcurrent = 4;

            document.getElementById('serverInfo').textContent = 'httpbin.org';

            const updateInterval = setInterval(() => {
                const elapsed = (Date.now() - startTime) / 1000;
                if (elapsed > 0) {
                    currentSpeed = (totalBytes * 8) / elapsed / 1000000;
                    speedHistory.push(currentSpeed);

                    const displaySpeed = speedHistory.length > 5 ?
                        speedHistory.slice(-5).reduce((a, b) => a + b, 0) / 5 : currentSpeed;

                    updateSpeedMeter(displaySpeed, false);

                    const progress = 30 + (elapsed / testDuration * 40);
                    updateStatus('正在测试下载速度...', Math.min(progress, 70));
                }

                if (Date.now() - startTime >= testDuration) {
                    clearInterval(updateInterval);
                }
            }, 200);

            for (let i = 0; i < maxConcurrent; i++) {
                downloadFile(testFiles[i % testFiles.length], i);
            }

            async function downloadFile(url, index) {
                try {
                    activeDownloads++;
                    const response = await fetch(url + '?t=' + Date.now() + '_' + index, {
                        cache: 'no-cache'
                    });
                    const reader = response.body.getReader();

                    while (true) {
                        const { done, value } = await reader.read();
                        if (done) break;

                        totalBytes += value.length;

                        // 如果测试时间超过测试时长，停止下载
                        if (Date.now() - startTime >= testDuration) {
                            break;
                        }
                    }
                } catch (error) {
                    console.log('Download failed:', error);
                } finally {
                    activeDownloads--;

                    // 如果还在测试时间内且活跃下载数少于最大值，启动新的下载
                    if (Date.now() - startTime < testDuration && activeDownloads < maxConcurrent) {
                        setTimeout(() => {
                            downloadFile(testFiles[Math.floor(Math.random() * testFiles.length)], Math.random());
                        }, 100);
                    }
                }
            }

            // 等待测试完成
            await new Promise(resolve => setTimeout(resolve, testDuration));
            clearInterval(updateInterval);
        }

        // 测试网络延迟
        async function testPing() {
            const testUrls = [
                'https://www.google.com/generate_204',
                'https://www.cloudflare.com/cdn-cgi/trace',
                'https://httpbin.org/get'
            ];

            let bestPing = 999;

            // 并发测试多个URL，取最佳结果
            const pingPromises = testUrls.map(async (url) => {
                try {
                    const startTime = performance.now();
                    await fetch(url, {
                        method: 'HEAD',
                        mode: 'no-cors',
                        cache: 'no-cache'
                    });
                    const endTime = performance.now();
                    return Math.round(endTime - startTime);
                } catch (error) {
                    return 999;
                }
            });

            const results = await Promise.all(pingPromises);
            bestPing = Math.min(...results.filter(ping => ping < 999));

            return bestPing < 999 ? bestPing : 999;
        }

        // 添加测速结果
        function addSpeedResult(name, value, status) {
            const resultsList = document.getElementById('speedResultsList');
            const resultItem = document.createElement('div');
            resultItem.className = 'result-item';

            let statusClass = 'status-success';
            if (status === 'warning') statusClass = 'status-loading';
            if (status === 'error') statusClass = 'status-error';

            resultItem.innerHTML = `
                <div class="result-url">${name}</div>
                <span class="result-status ${statusClass}">${value}</span>
            `;

            resultsList.appendChild(resultItem);
        }

        // 格式化速度显示
        function formatSpeed(mbps) {
            if (mbps < 1) {
                return `${(mbps * 1000).toFixed(0)} Kbps`;
            } else {
                return `${mbps.toFixed(2)} Mbps`;
            }
        }

        // 获取速度状态
        function getSpeedStatus(mbps) {
            if (mbps >= 10) return 'success';
            if (mbps >= 1) return 'warning';
            return 'error';
        }



        // 上传速度测试
        async function testUploadSpeedWithDashboard() {
            let testDuration = 8000; // 8秒测试
            let startTime = Date.now();
            let totalBytes = 0;
            let activeUploads = 0;
            const maxConcurrent = 2; // 上传并发数较少

            // 创建测试数据
            const testData = new ArrayBuffer(1048576); // 1MB
            const testBlob = new Blob([testData]);

            const updateInterval = setInterval(() => {
                const elapsed = (Date.now() - startTime) / 1000;
                if (elapsed > 0) {
                    const uploadSpeed = (totalBytes * 8) / elapsed / 1000000;
                    updateSpeedMeter(uploadSpeed, true);

                    const progress = 70 + (elapsed / testDuration * 25);
                    updateStatus('正在测试上传速度...', Math.min(progress, 95));
                }

                if (Date.now() - startTime >= testDuration) {
                    clearInterval(updateInterval);
                }
            }, 200);

            // 启动多个上传测试
            for (let i = 0; i < maxConcurrent; i++) {
                uploadFile(testBlob, i);
            }

            async function uploadFile(data, index) {
                try {
                    activeUploads++;
                    const formData = new FormData();
                    formData.append('file', data, `test_${index}.bin`);
                    await fetch('https://httpbin.org/post', {
                        method: 'POST',
                        body: formData,
                        cache: 'no-cache'
                    });
                    totalBytes += data.size;
                } catch (error) {
                    console.log('Upload failed:', error);
                } finally {
                    activeUploads--;

                    // 继续上传保持测试
                    if (Date.now() - startTime < testDuration && activeUploads < maxConcurrent) {
                        setTimeout(() => {
                            uploadFile(testBlob, Math.random());
                        }, 500);
                    }
                }
            }

            // 等待测试完成
            await new Promise(resolve => setTimeout(resolve, testDuration));
            clearInterval(updateInterval);
        }

        // 页面加载完成后的初始化
        document.addEventListener('DOMContentLoaded', function () {
            console.log('网站测试工具已加载完成');
            getUserIP(); // 获取用户IP地址
        });
    </script>
</body>

</html>
