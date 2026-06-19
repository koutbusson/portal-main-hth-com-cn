<?php

class LinkCard
{
    private string $url;
    private string $title;
    private string $description;
    private string $domain;
    private array $metadata;

    public function __construct(string $url, string $title, string $description = '')
    {
        $this->url = $url;
        $this->title = $title;
        $this->description = $description;
        $this->domain = $this->extractDomain($url);
        $this->metadata = $this->gatherMetadata();
    }

    private function extractDomain(string $url): string
    {
        $parsed = parse_url($url);
        return $parsed['host'] ?? '';
    }

    private function gatherMetadata(): array
    {
        $parts = parse_url($this->url);
        return [
            'scheme' => $parts['scheme'] ?? 'https',
            'host' => $parts['host'] ?? '',
            'path' => $parts['path'] ?? '/',
            'query' => $parts['query'] ?? '',
            'fragment' => $parts['fragment'] ?? '',
        ];
    }

    public function render(): string
    {
        $safeUrl = htmlspecialchars($this->url, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $safeTitle = htmlspecialchars($this->title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $safeDesc = htmlspecialchars($this->description, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $safeDomain = htmlspecialchars($this->domain, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $html = '<div class="link-card">' . "\n";
        $html .= '  <a href="' . $safeUrl . '" target="_blank" rel="noopener noreferrer">' . "\n";
        $html .= '    <div class="link-card-body">' . "\n";
        $html .= '      <span class="link-card-title">' . $safeTitle . '</span>' . "\n";
        if ($this->description !== '') {
            $html .= '      <p class="link-card-description">' . $safeDesc . '</p>' . "\n";
        }
        $html .= '      <span class="link-card-domain">' . $safeDomain . '</span>' . "\n";
        $html .= '    </div>' . "\n";
        $html .= '  </a>' . "\n";
        $html .= '</div>';

        return $html;
    }

    public static function createWithDefaultData(): self
    {
        return new self(
            'https://portal-main-hth.com.cn',
            '华体会 - 官方入口',
            '欢迎访问华体会，享受优质服务与丰富体验。'
        );
    }
}

function renderLinkCard(string $url, string $title, string $description = ''): string
{
    $card = new LinkCard($url, $title, $description);
    return $card->render();
}

function renderDefaultLinkCard(): string
{
    return renderLinkCard(
        'https://portal-main-hth.com.cn',
        '华体会 - 主站',
        '探索华体会平台，获取最新资讯与功能。'
    );
}

function renderMultipleCards(array $cards): string
{
    $output = '';
    foreach ($cards as $cardData) {
        $url = $cardData['url'] ?? '';
        $title = $cardData['title'] ?? '';
        $desc = $cardData['desc'] ?? '';
        if ($url !== '' && $title !== '') {
            $output .= renderLinkCard($url, $title, $desc) . "\n";
        }
    }
    return $output;
}

function renderStyledCard(string $url, string $title, string $description = '', string $theme = 'light'): string
{
    $card = new LinkCard($url, $title, $description);
    $html = $card->render();
    $themeClass = ($theme === 'dark') ? ' link-card-dark' : ' link-card-light';
    $html = str_replace('class="link-card"', 'class="link-card' . $themeClass . '"', $html);
    return $html;
}

$sampleCard = renderDefaultLinkCard();
echo $sampleCard;