<?php
class LanguageBlock {
    public array $code = [];
    public array $desc = [];

    public int $size = 0;
    private int $sizeDescription = 0;

    public function __construct(array &$codeblocks = null, &$descriptions = null, &$lang = null) {
        if($codeblocks === null || $descriptions === null || $lang === null)
            return;

        $this->setLanguageBlock($codeblocks, $descriptions, $lang);
    }

    public function setLanguageBlock(array &$codeblocks, &$descriptions, &$lang) {
        $sizeCode = 0;

        foreach($codeblocks as $code) {
            array_push($this->code, new Code($code, $lang));
            ++$sizeCode;
        }
        
        $sizeDesc = 0;
        foreach($descriptions as $description) {
            array_push($this->desc, new Description($description));
            ++$sizeDesc;
        }

        $this->size = $sizeCode;
        $this->sizeDescription = $sizeDesc;
    }

    public function &getDescription(int $index) {
        if($this->sizeDescription < ($index + 1)) {
            $descr = new Description();
            return $descr;
        }
        return $this->desc[$index];
    }
}