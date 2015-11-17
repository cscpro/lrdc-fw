<?php
header("Pragma: cache");
header( "Cache-Control: public, max-age=604800" );

$files = 'static' . str_replace( '%20', ' ', $_csc->syntax ) . '.' . $_csc->format;

// Functions
function pfx($t,$i) {
	$r = $i . ':\\1;';
	$t = 'x' . $t;
	if ( strpos( $t, 'm' ) ) $r .= '-moz-' . $i . ':\\1;';
	if ( strpos( $t, 'w' ) ) $r .= '-webkit-' . $i . ':\\1;';
	if ( strpos( $t, 'o' ) ) $r .= '-o-' . $i . ':\\1;';
	if ( strpos( $t, 's' ) ) $r .= '-ms-' . $i . ':\\1;';
	return $r;
}


// When the MAD begins..
if ( ! file_exists( $files ) ) echo $files;//header( 'Location: /' );
elseif ( in_array( $_csc->format, array( 'png', 'jpg', 'jpeg', 'gif' ) ) ) {
	header( 'Content-type: image/' . $_csc->format );
	readfile( $files );

} elseif ( in_array( $_csc->format, array( 'css', 'js' ) ) ) {
	$bc = file_get_contents( $files );
	$xp = xp( '/**', '**/' );

	if ( $_csc->format == 'css' ) {
		header( 'Content-type: text/css' );
		$list = array(
			'border-radius' => 'wm',
			'box-sizing' => 'wm',
			'column-count' => 'wm',
			'transform' => 'woms',
			'transition' => 'woms'
		);

		foreach ( $list as $x => $v ) {
			$preg[] = '/' . $x . ':(.*?);/is';
			$replace[] = pfx( $v, $x );
		}

		$bc = preg_replace( $preg, $replace, $bc );

	} else header( 'Content-type: text/javascript' );

	$r = preg_replace( '#/\*.*?\*/#s', '', $bc ); // Remove comments
	$r = preg_replace( '/\s*([{}|:;,])\s+/', '$1', $r ); // Remove whitespace
	$r = preg_replace( '/\s\s+(.*)/', '$1', $r ); // Remove trailing whitespace at the start
	$r = str_replace( ';}', '}', $r ); // Remove unnecessary ;'s
	echo ( $xp ? '/*' . $xp . '*/' . "\n" : '' ) . $r;
//	echo $bc;

} elseif ( in_array( $_csc->format, array( 'ttf', 'eot', 'otf', 'woff' ) ) ) {
	header( 'Content-type: application/font-' . $_csc->format );	
	readfile( $files );

} else {
	header( 'Content-type: application/' . $_csc->format );	
	readfile( $files );

} // END of code 
