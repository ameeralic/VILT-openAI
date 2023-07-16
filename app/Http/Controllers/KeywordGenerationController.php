<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
require_once 'vendor/autoload.php';

use Google\Ads\GoogleAds\GoogleAdsClient;
use Google\Ads\GoogleAds\Util\V6\ResourceNames;
use Google\Ads\GoogleAds\V6\Enums\KeywordPlanNetworkEnum\KeywordPlanNetwork;
use Google\Ads\GoogleAds\V6\Services\GenerateKeywordIdeasRequest;
use Google\Ads\GoogleAds\V6\Services\KeywordPlanIdeaServiceClient;
use Google\Ads\GoogleAds\V6\Services\LanguageConstantServiceClient;
use Google\Ads\GoogleAds\V6\Services\KeywordPlanServiceClient;

class KeywordGenerationController extends Controller
{
    //
    public function generateKeywordIdeas($customerId, $keywordTexts)
    {
        // Create a Google Ads API client.
        $googleAdsClient = GoogleAdsClient::loadFromStorage();

        // Create the KeywordPlanIdeaService client.
        $keywordPlanIdeaServiceClient = $googleAdsClient->getKeywordPlanIdeaServiceClient();

        // Create a new request for generating keyword ideas.
        $request = new GenerateKeywordIdeasRequest([
            'customerId' => $customerId,
            'language' => LanguageConstantServiceClient::languageConstantName(
                $customerId,
                'en' // Replace 'en' with your desired language code.
            ),
            'keywordPlanNetwork' => KeywordPlanNetwork::GOOGLE_SEARCH_AND_PARTNERS,
            'keywordAndUrlSeed' => [
                'keywordPlanAdGroup' => ResourceNames::forKeywordPlanAdGroup(
                    $customerId,
                    'INSERT_KEYWORD_PLAN_ID',
                    'INSERT_AD_GROUP_ID'
                ),
                'keywords' => $keywordTexts
            ]
        ]);

        // Generate keyword ideas.
        $response = $keywordPlanIdeaServiceClient->generateKeywordIdeas($request);

        // Print the results.
        foreach ($response->getKeywordIdeas() as $keywordIdea) {
            printf(
                "Text: '%s', Avg Monthly Searches: %d, Competition: %d, Ad Group Competition: %d\n",
                $keywordIdea->getText(),
                $keywordIdea->getKeywordIdeaMetrics()->getAvgMonthlySearches(),
                $keywordIdea->getKeywordIdeaMetrics()->getCompetition(),
                $keywordIdea->getKeywordIdeaMetrics()->getAdGroupCompetition()
            );
        }
    }
}