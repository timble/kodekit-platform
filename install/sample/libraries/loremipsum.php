<?php
/*
 * Copyright 2010 Oliver C Dodd http://01001111.net
 * Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
 */
class LoremIpsum {

    /* The Lorem Ipsum Standard Paragraph */
    protected $standard = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
    private $li_words = array("a", "ac", "accumsan", "ad", "adipiscing",
        "aenean", "aliquam", "aliquet", "amet", "ante", "aptent", "arcu",
        "at", "auctor", "augue", "bibendum", "blandit", "class", "commodo",
        "condimentum", "congue", "consectetur", "consequat", "conubia",
        "convallis", "cras", "cubilia", "cum", "curabitur", "curae",
        "cursus", "dapibus", "diam", "dictum", "dictumst", "dignissim",
        "dis", "dolor", "donec", "dui", "duis", "egestas", "eget",
        "eleifend", "elementum", "elit", "enim", "erat", "eros", "est",
        "et", "etiam", "eu", "euismod", "facilisi", "facilisis", "fames",
        "faucibus", "felis", "fermentum", "feugiat", "fringilla", "fusce",
        "gravida", "habitant", "habitasse", "hac", "hendrerit",
        "himenaeos", "iaculis", "id", "imperdiet", "in", "inceptos",
        "integer", "interdum", "ipsum", "justo", "lacinia", "lacus",
        "laoreet", "lectus", "leo", "libero", "ligula", "litora",
        "lobortis", "lorem", "luctus", "maecenas", "magna", "magnis",
        "malesuada", "massa", "mattis", "mauris", "metus", "mi",
        "molestie", "mollis", "montes", "morbi", "mus", "nam", "nascetur",
        "natoque", "nec", "neque", "netus", "nibh", "nisi", "nisl", "non",
        "nostra", "nulla", "nullam", "nunc", "odio", "orci", "ornare",
        "parturient", "pellentesque", "penatibus", "per", "pharetra",
        "phasellus", "placerat", "platea", "porta", "porttitor", "posuere",
        "potenti", "praesent", "pretium", "primis", "proin", "pulvinar",
        "purus", "quam", "quis", "quisque", "rhoncus", "ridiculus",
        "risus", "rutrum", "sagittis", "sapien", "scelerisque", "sed",
        "sem", "semper", "senectus", "sit", "sociis", "sociosqu",
        "sodales", "sollicitudin", "suscipit", "suspendisse", "taciti",
        "tellus", "tempor", "tempus", "tincidunt", "torquent", "tortor",
        "tristique", "turpis", "ullamcorper", "ultrices", "ultricies",
        "urna", "ut", "varius", "vehicula", "vel", "velit", "venenatis",
        "vestibulum", "vitae", "vivamus", "viverra", "volutpat",
        "vulputate");
    private $punctuation = array(".", "?");
    private $_n = '
';

    public function LoremIpsum() {}

    /**
     * Get a random word
     */
    public function randomWord() {
        return $this->li_words[rand(0, count($this->li_words) - 1)];
    }

    /**
     * Get a random punctuation mark
     */
    public function randomPunctuation() {
        return $this->punctuation[rand(0, count($this->punctuation) - 1)];
    }

    /**
     * Get a string of words
     *
     * @param count
     *            - the number of words to fetch
     */
    public function words($count) {
        $s = "";
        while ($count-- > 0)
            $s .= $this->randomWord() . " ";
        return trim($s);
    }

    /**
     * Get a sentence fragment
     */
    public function sentenceFragment() {
        return $this->words(rand(0,10) + 3);
    }

    /**
     * Get a sentence
     */
    public function sentence() {
        // first word
        $s = $this->randomWord();
        $s = strtoupper(substr($s,0,1)) . substr($s,1) . " ";
        // commas?
        if (rand(0,1)) {
            $r = rand(0,3) + 1;
            for ($i = 0; $i < $r; $i++)
                $s .= $this->sentenceFragment() . ", ";
        }
        // last fragment + punctuation
        return $s . $this->sentenceFragment() . $this->randomPunctuation();
    }

    /**
     * Get multiple sentences
     *
     * @param count
     *            - the number of sentences
     */
    public function sentences($count) {
        $s = "";
        while ($count-- > 0)
            $s .= $this->sentence() . "  ";
        return trim($s);
    }

    /**
     * Get a paragraph
     *
     * @useStandard - get the standard Lorem Ipsum paragraph?
     */
    public function paragraph($useStandard=false) {
        $s = "";
        if ($useStandard) {
            $s = $this->standard;
        } else {
            $s = $this->sentences(rand(0,3) + 2);
        }
        return $s;
    }

    /**
     * Get multiple paragraphs
     *
     * @param count
     *            - the number of paragraphs
     * @useStandard - lead with the standard Lorem Ipsum paragraph?
     */
    public function paragraphs($count, $useStandard=false) {
        $s = "";
        while ($count-- > 0) {
            $s .= $this->paragraph($useStandard) . $this->_n . $this->_n;
            $useStandard = false;
        }
        return trim($s);
    }
}
?>