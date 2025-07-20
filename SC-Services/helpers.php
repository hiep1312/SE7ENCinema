<?php
    if (!function_exists('getYoutubeEmbedUrl')) {
        /**
         * Generate an embeddable YouTube iframe URL from a YouTube video link.
         *
         * This function supports standard YouTube URLs such as:
         * - https://www.youtube.com/watch?v=VIDEO_ID
         * - https://youtu.be/VIDEO_ID
         *
         * It parses the video ID either from the `v` query parameter (for standard URLs),
         * or from the path (for shortened youtu.be URLs). If the video ID cannot be found
         * or the URL does not appear to be from YouTube, it returns null.
         *
         * @param string $link The original YouTube video URL.
         *
         * @return string|null The embeddable iframe URL (e.g., https://www.youtube.com/embed/VIDEO_ID),
         *                     or null if a valid video ID could not be determined.
         */
        function getYoutubeEmbedUrl(string $link): ?string{
            $analyticalURL = parse_url($link);
            parse_str($analyticalURL['query'] ?? '', $params);
            if ((empty($params['v']) && empty($analyticalURL['path'])) && (!stripos($analyticalURL['host'] ?? '', 'youtube.com') && !stripos($analyticalURL['path'] ?? '', 'youtube.com'))) return null;

            $urlEmbed = $params['v'] ?? basename($analyticalURL['path']);

            return "https://www.youtube.com/embed/{$urlEmbed}";
        }
    }

    if(!function_exists('getURLPath')){
        /**
         * Generate a full URL path from a given URL string, with conditional parsing.
         *
         * This function analyzes the given URL string and determines whether it should be
         * returned as-is or converted into a full application URL using Laravel's `url()` helper.
         *
         * - If the URL is null or empty, it returns null.
         * - If the parsed URL contains components outside of 'path', 'query', or 'fragment',
         *   or if the path (excluding the basename) contains a dot (.), it is considered a full URL
         *   and returned as-is.
         * - Otherwise, the URL is treated as a relative path and passed to `url()` to generate a full URL.
         *
         * @param string|null $url The input URL or path to evaluate.
         *
         * @return string|null The original URL or a fully qualified URL, or null if input is empty.
         */
        function getURLPath(?string $url = null): ?string{
            if(is_null($url) || empty($url)) return null;

            $analyticalURL = parse_url($url);
            foreach($analyticalURL as $component => $value) if(!in_array($component, ['path', 'query', 'fragment']) || ($component === "path" && str_contains(substr($value, 0, -strlen(basename($value))), '.'))) return $url;
            return url($url);
        }
    }
