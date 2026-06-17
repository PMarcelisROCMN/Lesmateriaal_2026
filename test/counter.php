<?php

class Counter
{

    private int $count = 0;

    public function __construct(int $count = 0)
    {
        $this->count = $count;
    }

    public function addToCount()
    {
        $this->count++;
    }

    public function removeFromCount()
    {
        $this->count--;
    }

    /**
     * This method will return the count of the class instance
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }
}


$peteCounter = new Counter();

echo $peteCounter->getCount();
echo '<br>';
$peteCounter->addToCount(); // 1
$peteCounter->addToCount(); // 2
$peteCounter->addToCount(); // 3
$peteCounter->removeFromCount(); // 2
echo '<br>';
echo $peteCounter->getCount();