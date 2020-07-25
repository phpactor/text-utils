<?php

namespace Phpactor\TextUtils\Tests\Unit;

use OutOfBoundsException;
use PHPUnit\Framework\TestCase;
use Phpactor\TestUtils\ExtractOffset;
use Phpactor\TextUtils\LineAtOffset;

class LineAtOffsetTest extends TestCase
{
    /**
     * @dataProvider provideLineAtOffset
     */
    public function testLineAtOffset(string $text, string $expectedWord)
    {
        [ $text, $offset ] = ExtractOffset::fromSource($text);

        $this->assertEquals($expectedWord, LineAtOffset::lineAtOffset($text, --$offset));
    }

    public function provideLineAtOffset()
    {
        yield [
            'hello thi<>s is',
            'hello this is',
        ];
        yield 'first char' => [
            'h<>ello this is',
            'hello this is',
        ];
        yield 'last char' => [
            'hello this is<>',
            'hello this is',
        ];
        yield [
            <<<'EOT'
<?php

Hello
Thi<>s is my line

Thanks
EOT
            , 'This is my line',
        ];
        yield 'multibyte 1' => [
            <<<'EOT'
<?php

转注字 / 轉注字
转<>注字 / 轉注字

Thanks
EOT
            , '转注字 / 轉注字',
        ];
        yield 'multibyte 2' => [
            <<<'EOT'
<?php

转注字 / 轉注字
转注字 / 轉注字<>

Thanks
EOT
            , '转注字 / 轉注字',
        ];
    }

    public function testOutOfRange(): void
    {
        $this->expectException(OutOfBoundsException::class);
        LineAtOffset::lineAtOffset('a', 1);
    }
}
