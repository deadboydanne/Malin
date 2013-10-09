<?php
/**
 * Helpers for the template file.
 */
$ma->data['header'] = '<h1>Header: Malin</h1>';
$ma->data['footer'] = '<p>Footer: &copy; Malin by Daniel Schäder (dasc13@dbwebb.se)</p>
<p>Tools: 
<a href="http://validator.w3.org/check/referer">html5</a>
<a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css3">css3</a>
<a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css21">css21</a>
<a href="http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance">unicorn</a>
<a href="http://validator.w3.org/checklink?uri={$ma->request->current_url}">links</a>
<a href="http://qa-dev.w3.org/i18n-checker/index?async=false&amp;docAddr={$ly->request->current_url}">i18n</a>
<!-- <a href="link?">http-header</a> -->
<a href="http://csslint.net/">css-lint</a>
<a href="http://jslint.com/">js-lint</a>
<a href="http://jsperf.com/">js-perf</a>
<a href="http://www.workwithcolor.com/hsl-color-schemer-01.htm">colors</a>
<a href="http://dbwebb.se/style">style</a>
</p>

<p>Docs:
<a href="http://www.w3.org/2009/cheatsheet">cheatsheet</a>
<a href="http://dev.w3.org/html5/spec/spec.html">html5</a>
<a href="http://www.w3.org/TR/CSS2">css2</a>
<a href="http://www.w3.org/Style/CSS/current-work#CSS3">css3</a>
<a href="http://php.net/manual/en/index.php">php</a>
<a href="http://www.sqlite.org/lang.html">sqlite</a>
<a href="http://www.blueprintcss.org/">blueprint</a>
</p>';


/**
 * Print debuginformation from the framework.
 */
function get_debug() {
  $ma = CMalin::Instance();  
  $html = null;
  if(isset($ma->config['debug']['db-num-queries']) && $ma->config['debug']['db-num-queries'] && isset($ma->db)) {
    $html .= "<p>Database made " . $ma->db->GetNumQueries() . " queries.</p>";
  }    
  if(isset($ma->config['debug']['db-queries']) && $ma->config['debug']['db-queries'] && isset($ma->db)) {
    $html .= "<p>Database made the following queries.</p><pre>" . implode('<br/><br/>', $ma->db->GetQueries()) . "</pre>";
  }    
  if(isset($ma->config['debug']['malin']) && $ma->config['debug']['malin']) {
    $html .= "<hr><h3>Debuginformation</h3><p>The content of CMalin:</p><pre>" . htmlent(print_r($ma, true)) . "</pre>";
  }    
  return $html;
}

/**
 * Create a url by prepending the base_url.
 */
function base_url($url) {
  return CMalin::Instance()->request->base_url . trim($url, '/');
}

/**
 * Return the current url.
 */
function current_url() {
  return CMalin::Instance()->request->current_url;
}

/**
 * Render all views.
 */
function render_views() {
  return CMalin::Instance()->views->Render();
}