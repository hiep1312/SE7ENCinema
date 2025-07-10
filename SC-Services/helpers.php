<?php
    if (! function_exists('getYoutubeEmbedUrl')) {
        /**
         * Convert a standard YouTube video URL into an embeddable YouTube iframe URL.
         *
         * This function extracts the `v` parameter from a YouTube URL and builds the corresponding
         * embed URL in the format: https://www.youtube.com/embed/{video_id}
         *
         * @param string $link The full YouTube video URL (e.g., https://www.youtube.com/watch?v=VIDEO_ID).
         *
         * @return string|null The embeddable YouTube iframe URL, or null if the video ID is not found.
         */
        function getYoutubeEmbedUrl(string $link): ?string{
            parse_str(parse_url($link, PHP_URL_QUERY), $params);
            if (empty($params['v'])) return null;

            return "https://www.youtube.com/embed/{$params['v']}";
        }
    }
