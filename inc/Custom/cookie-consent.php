<?php
$cookie_consent = get_field('cookie_consent', 'option') ?? '';
//cookie_consent

/* ==========================================
   PROFESSIONAL COOKIE CONSENT SYSTEM
========================================== */
if ( $cookie_consent == 'true') :

	function pro_cookie_assets() {

		/* ---------- CSS ---------- */

		wp_register_style('pro-cookie-style', false);
		wp_enqueue_style('pro-cookie-style');

		$css = "
		.cc-overlay {
			position: fixed;
			inset: 0;
			background: rgba(0,0,0,0.6);
			display: flex;
			align-items: center;
			justify-content: center;
			z-index: 9999;
			opacity:0;
			visibility:hidden;
			transition: all .3s ease;
		}
		.cc-overlay.active {
			opacity:1;
			visibility:visible;
		}
		.cc-box {
			background:#fff;
			padding:30px;
			max-width:520px;
			width:90%;
			border-radius:12px;
			transform:translateY(20px);
			transition:.3s ease;
			position: absolute;
			bottom: 20px;
			left: 20px;
		}
		.cc-overlay.active .cc-box {
			transform:translateY(0);
		}
		.cc-buttons {
			margin-top:20px;
			display:flex;
			gap:10px;
			flex-wrap:wrap;
		}
		.cc-btn {
			padding:10px 16px;
			border-radius:6px;
			cursor:pointer;
			border:none;
		}
		.cc-accept { background:#000; color:#fff; }
		.cc-reject { background:#ccc; }
		.cc-link { background:none; text-decoration:underline; }

		.cc-toggle { margin-top:15px; }
		.cc-manage {
			position:fixed;
			bottom:20px;
			left:90px;
			background:transparent;
			color:#fff;
			padding:8px 14px;
			border-radius:20px;
			cursor:pointer;
			font-size:34px;
			z-index:9998;
			grayscale(100%);
			transition: all .3s ease;
		}
		";

		wp_add_inline_style('pro-cookie-style', $css);

		/* ---------- JS ---------- */

		wp_register_script('pro-cookie-js', '', [], false, true);
		wp_enqueue_script('pro-cookie-js');

		$js = "
		const CC_EXPIRY_DAYS = 365;

		function setConsent(data){
			const expiry = new Date();
			expiry.setTime(expiry.getTime() + (CC_EXPIRY_DAYS*24*60*60*1000));
			localStorage.setItem('cc_data', JSON.stringify({
				value:data,
				expiry: expiry.getTime()
			}));
		}

		function getConsent(){
			const item = localStorage.getItem('cc_data');
			if(!item) return null;

			const parsed = JSON.parse(item);
			if(new Date().getTime() > parsed.expiry){
				localStorage.removeItem('cc_data');
				return null;
			}
			return parsed.value;
		}

		function activateScripts(category){
			document.querySelectorAll('script[type=\"text/plain\"][data-category=\"'+category+'\"]').forEach(script=>{
				const newScript = document.createElement('script');
				newScript.text = script.text;
				document.body.appendChild(newScript);
				script.remove();
			});
		}

		function applyConsent(consent){
			if(consent.analytics) activateScripts('analytics');
			if(consent.marketing) activateScripts('marketing');
		}

		document.addEventListener('DOMContentLoaded', function(){

			const overlay = document.getElementById('ccOverlay');
			const accept = document.getElementById('ccAccept');
			const reject = document.getElementById('ccReject');
			const prefsBtn = document.getElementById('ccPrefsBtn');
			const savePrefs = document.getElementById('ccSavePrefs');
			const manageBtn = document.getElementById('ccManage');

			const analyticsToggle = document.getElementById('ccAnalytics');
			const marketingToggle = document.getElementById('ccMarketing');
			const prefsBox = document.getElementById('ccPrefs');

			const consent = getConsent();

			if(consent){
				applyConsent(consent);
			} else {
				overlay.classList.add('active');
			}

			accept.onclick = function(){
				const consentData = {analytics:true, marketing:true};
				setConsent(consentData);
				applyConsent(consentData);
				overlay.classList.remove('active');
			};

			reject.onclick = function(){
				const consentData = {analytics:false, marketing:false};
				setConsent(consentData);
				overlay.classList.remove('active');
			};

			prefsBtn.onclick = function(){
				prefsBox.classList.toggle('active');
			};

			savePrefs.onclick = function(){
				const consentData = {
					analytics: analyticsToggle.checked,
					marketing: marketingToggle.checked
				};
				setConsent(consentData);
				applyConsent(consentData);
				overlay.classList.remove('active');
			};

			manageBtn.onclick = function(){
				overlay.classList.add('active');
			};

		});
		";

		wp_add_inline_script('pro-cookie-js', $js);
	}
	add_action('wp_enqueue_scripts', 'pro_cookie_assets');

	function pro_cookie_html(){ ?>
		
		<div id="ccOverlay" class="cc-overlay" role="dialog" aria-modal="true">
			<div class="cc-box">
				<h3>We value your privacy</h3>
				<p>We use essential cookies and optional cookies for analytics and marketing.</p>

				<div class="cc-buttons">
					<button id="ccAccept" class="cc-btn cc-accept">Accept All</button>
					<button id="ccReject" class="cc-btn cc-reject">Reject All</button>
					<button id="ccPrefsBtn" class="cc-btn cc-link">Preferences</button>
				</div>

				<div id="ccPrefs" class="cc-toggle">
					<label>
						<input type="checkbox" id="ccAnalytics">
						Analytics Cookies
					</label>
					<br><br>
					<label>
						<input type="checkbox" id="ccMarketing">
						Marketing Cookies
					</label>
					<br><br>
					<button id="ccSavePrefs" class="cc-btn cc-accept">Save Preferences</button>
				</div>
			</div>
		</div>

		<div id="ccManage" class="cc-manage"><img src='data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMTIyLjg4IDEyMi4yNSIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMTIyLjg4IDEyMi4yNSIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PGc+PHBhdGggZD0iTTEwMS43Nyw0OS4zOGMyLjA5LDMuMSw0LjM3LDUuMTEsNi44Niw1Ljc4YzIuNDUsMC42Niw1LjMyLDAuMDYsOC43LTIuMDFjMS4zNi0wLjg0LDMuMTQtMC40MSwzLjk3LDAuOTUgYzAuMjgsMC40NiwwLjQyLDAuOTYsMC40MywxLjQ3YzAuMTMsMS40LDAuMjEsMi44MiwwLjI0LDQuMjZjMC4wMywxLjQ2LDAuMDIsMi45MS0wLjA1LDQuMzVoMHYwYzAsMC4xMy0wLjAxLDAuMjYtMC4wMywwLjM4IGMtMC45MSwxNi43Mi04LjQ3LDMxLjUxLTIwLDQxLjkzYy0xMS41NSwxMC40NC0yNy4wNiwxNi40OS00My44MiwxNS42OXYwLjAxaDBjLTAuMTMsMC0wLjI2LTAuMDEtMC4zOC0wLjAzIGMtMTYuNzItMC45MS0zMS41MS04LjQ3LTQxLjkzLTIwQzUuMzEsOTAuNjEtMC43Myw3NS4xLDAuMDcsNTguMzRIMC4wN3YwYzAtMC4xMywwLjAxLTAuMjYsMC4wMy0wLjM4IEMxLDQxLjIyLDguODEsMjYuMzUsMjAuNTcsMTUuODdDMzIuMzQsNS4zNyw0OC4wOS0wLjczLDY0Ljg1LDAuMDdWMC4wN2gwYzEuNiwwLDIuODksMS4yOSwyLjg5LDIuODljMCwwLjQtMC4wOCwwLjc4LTAuMjMsMS4xMiBjLTEuMTcsMy44MS0xLjI1LDcuMzQtMC4yNywxMC4xNGMwLjg5LDIuNTQsMi43LDQuNTEsNS40MSw1LjUyYzEuNDQsMC41NCwyLjIsMi4xLDEuNzQsMy41NWwwLjAxLDAgYy0xLjgzLDUuODktMS44NywxMS4wOC0wLjUyLDE1LjI2YzAuODIsMi41MywyLjE0LDQuNjksMy44OCw2LjRjMS43NCwxLjcyLDMuOSwzLDYuMzksMy43OGM0LjA0LDEuMjYsOC45NCwxLjE4LDE0LjMxLTAuNTUgQzk5LjczLDQ3Ljc4LDEwMS4wOCw0OC4zLDEwMS43Nyw0OS4zOEwxMDEuNzcsNDkuMzh6IE01OS4yOCw1Ny44NmMyLjc3LDAsNS4wMSwyLjI0LDUuMDEsNS4wMWMwLDIuNzctMi4yNCw1LjAxLTUuMDEsNS4wMSBjLTIuNzcsMC01LjAxLTIuMjQtNS4wMS01LjAxQzU0LjI3LDYwLjEsNTYuNTIsNTcuODYsNTkuMjgsNTcuODZMNTkuMjgsNTcuODZ6IE0zNy41Niw3OC40OWMzLjM3LDAsNi4xMSwyLjczLDYuMTEsNi4xMSBzLTIuNzMsNi4xMS02LjExLDYuMTFzLTYuMTEtMi43My02LjExLTYuMTFTMzQuMTgsNzguNDksMzcuNTYsNzguNDlMMzcuNTYsNzguNDl6IE01MC43MiwzMS43NWMyLjY1LDAsNC43OSwyLjE0LDQuNzksNC43OSBjMCwyLjY1LTIuMTQsNC43OS00Ljc5LDQuNzljLTIuNjUsMC00Ljc5LTIuMTQtNC43OS00Ljc5QzQ1LjkzLDMzLjg5LDQ4LjA4LDMxLjc1LDUwLjcyLDMxLjc1TDUwLjcyLDMxLjc1eiBNMTE5LjMsMzIuNCBjMS45OCwwLDMuNTgsMS42LDMuNTgsMy41OGMwLDEuOTgtMS42LDMuNTgtMy41OCwzLjU4cy0zLjU4LTEuNi0zLjU4LTMuNThDMTE1LjcxLDM0LjAxLDExNy4zMiwzMi40LDExOS4zLDMyLjRMMTE5LjMsMzIuNHogTTkzLjYyLDIyLjkxYzIuOTgsMCw1LjM5LDIuNDEsNS4zOSw1LjM5YzAsMi45OC0yLjQxLDUuMzktNS4zOSw1LjM5Yy0yLjk4LDAtNS4zOS0yLjQxLTUuMzktNS4zOSBDODguMjMsMjUuMzMsOTAuNjQsMjIuOTEsOTMuNjIsMjIuOTFMOTMuNjIsMjIuOTF6IE05Ny43OSwwLjU5YzMuMTksMCw1Ljc4LDIuNTksNS43OCw1Ljc4YzAsMy4xOS0yLjU5LDUuNzgtNS43OCw1Ljc4IGMtMy4xOSwwLTUuNzgtMi41OS01Ljc4LTUuNzhDOTIuMDIsMy4xNyw5NC42LDAuNTksOTcuNzksMC41OUw5Ny43OSwwLjU5eiBNNzYuNzMsODAuNjNjNC40MywwLDguMDMsMy41OSw4LjAzLDguMDMgYzAsNC40My0zLjU5LDguMDMtOC4wMyw4LjAzcy04LjAzLTMuNTktOC4wMy04LjAzQzY4LjcsODQuMjIsNzIuMjksODAuNjMsNzYuNzMsODAuNjNMNzYuNzMsODAuNjN6IE0zMS45MSw0Ni43OCBjNC44LDAsOC42OSwzLjg5LDguNjksOC42OWMwLDQuOC0zLjg5LDguNjktOC42OSw4LjY5cy04LjY5LTMuODktOC42OS04LjY5QzIzLjIyLDUwLjY4LDI3LjExLDQ2Ljc4LDMxLjkxLDQ2Ljc4TDMxLjkxLDQ2Ljc4eiBNMTA3LjEzLDYwLjc0Yy0zLjM5LTAuOTEtNi4zNS0zLjE0LTguOTUtNi40OGMtNS43OCwxLjUyLTExLjE2LDEuNDEtMTUuNzYtMC4wMmMtMy4zNy0xLjA1LTYuMzItMi44MS04LjcxLTUuMTggYy0yLjM5LTIuMzctNC4yMS01LjMyLTUuMzItOC43NWMtMS41MS00LjY2LTEuNjktMTAuMi0wLjE4LTE2LjMyYy0zLjEtMS44LTUuMjUtNC41My02LjQyLTcuODhjLTEuMDYtMy4wNS0xLjI4LTYuNTktMC42MS0xMC4zNSBDNDcuMjcsNS45NSwzNC4zLDExLjM2LDI0LjQxLDIwLjE4QzEzLjc0LDI5LjY5LDYuNjYsNDMuMTUsNS44NCw1OC4yOWwwLDAuMDV2MGgwbC0wLjAxLDAuMTN2MEM1LjA3LDczLjcyLDEwLjU1LDg3LjgyLDIwLjAyLDk4LjMgYzkuNDQsMTAuNDQsMjIuODQsMTcuMjksMzgsMTguMWwwLjA1LDBoMHYwbDAuMTMsMC4wMWgwYzE1LjI0LDAuNzcsMjkuMzUtNC43MSwzOS44My0xNC4xOWMxMC40NC05LjQ0LDE3LjI5LTIyLjg0LDE4LjEtMzhsMC0wLjA1IHYwaDBsMC4wMS0wLjEzdjBjMC4wNy0xLjM0LDAuMDktMi42NCwwLjA2LTMuOTFDMTEyLjk4LDYxLjM0LDEwOS45Niw2MS41MSwxMDcuMTMsNjAuNzRMMTA3LjEzLDYwLjc0eiBNMTE2LjE1LDY0LjA0TDExNi4xNSw2NC4wNCBMMTE2LjE1LDY0LjA0TDExNi4xNSw2NC4wNHogTTU4LjIxLDExNi40Mkw1OC4yMSwxMTYuNDJMNTguMjEsMTE2LjQyTDU4LjIxLDExNi40MnoiLz48L2c+PC9zdmc+' alt="cookie" width="24" height="24" /></div>

	<?php }
	add_action('wp_footer', 'pro_cookie_html');

endif;
