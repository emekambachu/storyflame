<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        {{ config('app.name', 'StoryFlame (not set in env)') }}
    </title>
    @if (config('app.env') === 'production')
        <!-- Hotjar Tracking Code for StoryFlame -->
        <script>
            (function(h, o, t, j, a, r) {
                h.hj = h.hj || function() {
                    (h.hj.q = h.hj.q || []).push(arguments)
                }
                h._hjSettings = { hjid: 5019418, hjsv: 6 }
                a = o.getElementsByTagName('head')[0]
                r = o.createElement('script')
                r.async = 1
                r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv
                a.appendChild(r)
            })(window, document, 'https://static.hotjar.com/c/hotjar-', '.js?sv=')
        </script>

        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-HF1QTNJXS3"></script>
        <script>
            window.dataLayer = window.dataLayer || []

            function gtag() {
                dataLayer.push(arguments)
            }

            gtag('js', new Date())

            gtag('config', 'G-HF1QTNJXS3')
        </script>

        <!-- Clarity tracking code -->
        <script type="text/javascript">
            (function(c, l, a, r, i, t, y) {
                c[a] = c[a] || function() {
                    (c[a].q = c[a].q || []).push(arguments)
                }
                t = l.createElement(r)
                t.async = 1
                t.src = 'https://www.clarity.ms/tag/' + i
                y = l.getElementsByTagName(r)[0]
                y.parentNode.insertBefore(t, y)
            })(window, document, 'clarity', 'script', 'mq1nrqms1v')
        </script>
    @endif
    <script src="https://cdn.paddle.com/paddle/v2/paddle.js"></script>
    <script type="text/javascript">
        Paddle.Environment.set('sandbox')
        Paddle.Initialize({
            token: 'test_a80328e7467f6d2e32ee3131878',
            eventCallback: function(data) {
                if (data.name === 'checkout.closed') {
                    // Mitch: I didn't use checkout.completed as the webhook may not have updated yet
                    var event = new CustomEvent('paddleCheckoutClosed', { detail: data })
                    window.dispatchEvent(event)
                }
            },
        })
    </script>
</head>
<body>
@vite('resources/app.ts')
</body>
</html>
