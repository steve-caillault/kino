<?php

/**
 * Classe utilitaire sur le contenu HTML
 */

namespace App\UI\HTML;

use Illuminate\Support\Arr;

final class HTML {
    
    /**
     * Formate la chaine des attributs d'une balise HTML
     * @param array $attributes
     * @return string  
     */
    public static function attributes(array $attributes) : string
    {
        $str = '';
     
        foreach($attributes as $key => $value)
        {
        	if($value !== NULL)
        	{
            	$str .= ' ' . $key . '="' . htmlentities($value) . '"';
        	}
        }
        
        return trim($str);
    }
    
    /**
     * Retourne une balise HTML
     * @param string $tag Nom de la balise
     * @param array $attributes Propriété de la balise
     * @return string
     */
    public static function tag(string $tag, array $attributes = []) : string
    {
    	$content = Arr::get($attributes, 'content');
    	unset($attributes['content']);
    	
    	return strtr('<:tag :attributes>:content</:tag>', [
    		':tag' => $tag,
    		':attributes' => self::attributes($attributes),
    		':content' => $content,
    	]);
    }
    
    /**
     * Retourne le HTML d'une ancre
     * @param string $uri
     * @param string $label Texte de l'ancre
     * @param array $attributes
     * @return string
     */
    public static function anchor(string $uri, string $label, array $attributes = []) : string
    {
        if(! str_starts_with($uri, 'http'))
        {
            $uri = url($uri);
        }

    	$attributes = self::attributes(array_merge([
    		'href' => $uri,
    	], $attributes));
    	
    	return strtr('<a :attributes>:label</a>', [
    		':attributes' 	=> $attributes,
    		':label'		=> $label,
    	]);
    }
    
    /**
     * Retourne le HTML d'une balise image
     * @param string $path Chemin ou URL de l'image
     * @param array $attributes 
     * @return string
     */
    public static function image(string $path, array $attributes = []) : string
    {
    	if(strpos($path, 'data:') !== 0)
    	{
    		$path = url($path);
    	}
    	
    	$prepareAttributes = array_merge([
    		'src' => $path,
    	], $attributes);
    	
    	return strtr('<img :attributes />', [
    		':attributes' => self::attributes($prepareAttributes),
    	]);
    }
    
    /**
     * Retourne le HTML d'une balise script
     * @param string $url
     * @param array $options (defer, async)
     * @return string
     */
    public static function script(string $url, array $options = []) : string
    {
        if(! str_starts_with($url, 'http'))
        {
            $url = url($url);
        }

        $attributes = self::attributes(array_merge([
        	'src'   => $url,
        ], $options));
        
        return strtr('<script :attributes></script>', [
          ':attributes' => $attributes,  
        ]);
    }
    
    /**
     * Retourne le HTML d'une balise link
     * @param string $url
     * @param array $options (type, rel...)
     * @return string
     */
    public static function link(string $url, array $options = []) : string
    {
        if(! str_starts_with($url, 'http'))
        {
            $url = url($url);
        }

        $attributes = self::attributes(array_merge([
        	'href' => $url,
        ], $options));
        
        return strtr('<link :attributes />', [
            ':attributes' => $attributes,
        ]); 
    }
    
    /**
     * Retourne le HTML d'une balise style
     * @param string $url
     * @param array $options (media)
     * @return string
     */
    public static function style($url, array $options = []) : string
    {
        $linkOptions = array_merge([
            'rel'   => 'stylesheet',
            'type'  => 'text/css',
        ], $options);
        return self::link($url, $linkOptions);
    }
    
}