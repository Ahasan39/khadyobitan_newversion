import blogChiaPudding from "@/assets/blog-chia-pudding.jpg";
import blogColdPressedOils from "@/assets/blog-cold-pressed-oils.jpg";
import blogOrganicDates from "@/assets/blog-organic-dates.jpg";
import blogPinkSalt from "@/assets/blog-pink-salt.jpg";
import blogQuinoaBowl from "@/assets/blog-quinoa-bowl.jpg";
import blogJaggery from "@/assets/blog-jaggery.jpg";

export interface BlogPost {
  id: number;
  title: string;
  slug: string;
  excerpt: string;
  category: string;
  image: string;
  date: string;
  readTime: string;
  content: string;
}

export const blogPosts: BlogPost[] = [
  {
    id: 1,
    title: "5 Ways to Use Himalayan Pink Salt Beyond Cooking",
    slug: "himalayan-pink-salt-uses",
    excerpt: "Discover the versatile uses of Himalayan Pink Salt — from bath soaks to natural air purifiers. This ancient mineral has benefits that go far beyond seasoning.",
    category: "Tips & Tricks",
    image: blogPinkSalt,
    date: "Feb 10, 2026",
    readTime: "5 min read",
    content: `
## The Magic of Himalayan Pink Salt

Himalayan Pink Salt is much more than a kitchen staple. Mined from ancient sea beds in the Himalayan mountains, this mineral-rich salt contains over 84 trace minerals and elements that offer incredible health and wellness benefits.

### 1. Relaxing Bath Soak

Add 1-2 cups of Himalayan Pink Salt to a warm bath for a deeply relaxing soak. The minerals are absorbed through your skin, helping to:
- Relieve muscle tension and soreness
- Improve circulation
- Promote better sleep
- Detoxify the body naturally

### 2. Natural Air Purifier

Himalayan salt lamps are believed to release negative ions that help purify indoor air. Place them in bedrooms or living spaces to:
- Reduce allergens and dust particles
- Create a calming ambiance
- Improve mood and concentration

### 3. Exfoliating Body Scrub

Mix fine Himalayan Pink Salt with coconut oil and a few drops of essential oil for a luxurious body scrub. It gently removes dead skin cells while nourishing your skin with minerals.

### 4. Sole Water for Hydration

Create "sole water" by dissolving Himalayan salt in purified water overnight. Drinking a teaspoon of this concentrated solution each morning can help balance electrolytes and improve hydration at a cellular level.

### 5. Food Presentation on Salt Blocks

Heat or chill a Himalayan salt block to use as a unique serving platter. It imparts a subtle, complex flavor to foods while making a stunning presentation piece for dinner parties.

---

*Ready to explore premium Himalayan Pink Salt? Check out our collection of ethically sourced salts from the foothills of the Himalayas.*
    `,
  },
  {
    id: 2,
    title: "Overnight Chia Seed Pudding — 3 Delicious Recipes",
    slug: "chia-seed-pudding-recipes",
    excerpt: "Start your morning right with these easy overnight chia seed pudding recipes. Packed with omega-3, fiber, and natural sweetness from organic honey.",
    category: "Recipes",
    image: blogChiaPudding,
    date: "Feb 5, 2026",
    readTime: "4 min read",
    content: `
## Why Chia Seed Pudding?

Chia seeds are one of nature's most nutrient-dense superfoods. Just two tablespoons contain 11 grams of fiber, 4 grams of protein, and a generous dose of omega-3 fatty acids. When soaked overnight, they create a creamy, pudding-like texture that's perfect for breakfast.

### Recipe 1: Classic Honey Berry

**Ingredients:**
- 3 tbsp organic black chia seeds
- 1 cup almond milk
- 1 tbsp raw organic honey
- Fresh berries for topping

**Instructions:**
1. Mix chia seeds with almond milk and honey in a jar
2. Stir well and refrigerate overnight (at least 6 hours)
3. Top with fresh berries and a drizzle of honey before serving

### Recipe 2: Tropical Coconut Mango

**Ingredients:**
- 3 tbsp organic chia seeds
- 1 cup coconut milk
- 1 tbsp organic coconut sugar
- Fresh mango chunks and toasted coconut flakes

**Instructions:**
1. Combine chia seeds, coconut milk, and coconut sugar
2. Refrigerate overnight
3. Layer with fresh mango and coconut flakes

### Recipe 3: Chocolate Date Delight

**Ingredients:**
- 3 tbsp chia seeds
- 1 cup oat milk
- 1 tbsp cacao powder
- 2 Medjool dates, finely chopped

**Instructions:**
1. Blend oat milk with cacao powder until smooth
2. Mix in chia seeds and chopped dates
3. Refrigerate overnight and enjoy cold

---

*All ingredients are available in our shop — from premium chia seeds to raw organic honey and Medjool dates.*
    `,
  },
  {
    id: 3,
    title: "Why You Should Switch to Cold-Pressed Oils",
    slug: "cold-pressed-oils-benefits",
    excerpt: "Cold-pressed oils retain more nutrients than refined alternatives. Learn why making the switch can transform your health and cooking.",
    category: "Health",
    image: blogColdPressedOils,
    date: "Jan 28, 2026",
    readTime: "6 min read",
    content: `
## Understanding Cold-Pressed vs. Refined Oils

The way oil is extracted makes all the difference. Cold-pressed oils are extracted at low temperatures without chemicals, preserving their natural nutrients, flavor, and aroma. Refined oils, on the other hand, undergo high heat and chemical processing that strips away beneficial compounds.

### What Makes Cold-Pressed Better?

**Nutrient Retention:** Cold-pressing preserves vitamins (especially vitamin E), antioxidants, and essential fatty acids that are destroyed during high-heat refining.

**No Chemical Residues:** Unlike refined oils, cold-pressed varieties don't use hexane or other chemical solvents during extraction.

**Better Flavor:** The natural taste and aroma of the source ingredient — be it olives, coconuts, or mustard seeds — remains intact.

### Our Top Cold-Pressed Oils

#### Extra Virgin Olive Oil
Rich in monounsaturated fats and polyphenols. Perfect for salad dressings, light sautéing, and bread dipping. Contains oleocanthal, a natural anti-inflammatory compound.

#### Cold-Pressed Coconut Oil
A versatile kitchen essential with medium-chain triglycerides (MCTs) that support metabolism. Great for baking, frying, and even skincare.

#### Cold-Pressed Mustard Oil
A staple in South Asian cooking, rich in omega-3 fatty acids and known for its bold, pungent flavor. Excellent for pickles, marinades, and traditional curries.

### How to Store Cold-Pressed Oils

- Keep in dark glass bottles away from sunlight
- Store in a cool, dry place
- Use within 6 months of opening for best quality
- Never overheat — each oil has a specific smoke point

---

*Explore our collection of premium cold-pressed oils, sourced directly from organic farms.*
    `,
  },
  {
    id: 4,
    title: "The Complete Guide to Organic Dates",
    slug: "guide-to-organic-dates",
    excerpt: "From Medjool to Ajwa, explore the world of premium dates — their origins, nutritional profiles, and the best ways to enjoy them.",
    category: "Guides",
    image: blogOrganicDates,
    date: "Jan 20, 2026",
    readTime: "7 min read",
    content: `
## The World of Organic Dates

Dates have been a cherished food for thousands of years, prized across cultures for their natural sweetness, impressive nutrition, and incredible versatility. Let's explore the most popular varieties and how to enjoy them.

### Premium Varieties

#### Medjool Dates — "The King of Dates"
Large, soft, and incredibly sweet with a caramel-like flavor. Medjool dates are perfect for:
- Smoothies and energy balls
- Natural sweetener in baking
- Stuffed with nuts or cheese as appetizers

#### Ajwa Dates — "The Prophetic Date"
Smaller and darker with a unique, slightly dry texture and rich flavor. Ajwa dates are highly prized for their:
- Exceptional antioxidant content
- Traditional medicinal properties
- Rich, complex flavor profile

### Nutritional Benefits

Dates are packed with essential nutrients:
- **Fiber:** 7g per 100g, supporting digestive health
- **Potassium:** More per serving than bananas
- **Iron:** Helps prevent anemia
- **Natural sugars:** Glucose and fructose for quick, sustained energy
- **B vitamins:** Supporting metabolism and brain function

### Creative Ways to Use Dates

1. **Date Syrup:** Blend soaked dates with water for a natural sweetener
2. **Energy Balls:** Combine with nuts, cocoa, and coconut
3. **Stuffed Dates:** Fill with almond butter, cream cheese, or walnuts
4. **Smoothie Sweetener:** Add 2-3 dates to any smoothie for natural sweetness
5. **Baking:** Replace refined sugar in cakes and cookies

### Storage Tips

- Store at room temperature for up to a month
- Refrigerate for up to 6 months
- Freeze for up to a year
- Keep in an airtight container to prevent drying

---

*Browse our premium selection of organic Medjool and Ajwa dates, sourced from the finest farms.*
    `,
  },
  {
    id: 5,
    title: "Quinoa Bowl Recipe: A Protein-Packed Lunch",
    slug: "quinoa-bowl-recipe",
    excerpt: "A colorful, nutrient-dense quinoa bowl that's ready in 20 minutes. Perfect for meal prep and packed with plant-based protein.",
    category: "Recipes",
    image: blogQuinoaBowl,
    date: "Jan 15, 2026",
    readTime: "4 min read",
    content: `
## The Ultimate Quinoa Power Bowl

This vibrant quinoa bowl is not just beautiful — it's a nutritional powerhouse that keeps you energized throughout the day. Quinoa is one of the few plant foods that contains all nine essential amino acids, making it a complete protein source.

### Ingredients

- 1 cup organic quinoa, rinsed
- 2 cups water or vegetable broth
- 1 ripe avocado, sliced
- 1 cup chickpeas (cooked or canned)
- 1 cup cherry tomatoes, halved
- 1 cup fresh spinach or mixed greens
- ½ red bell pepper, diced
- 2 tbsp extra virgin olive oil
- 1 tbsp lemon juice
- Himalayan pink salt and organic black pepper to taste
- Optional: organic turmeric powder, sesame seeds

### Instructions

1. **Cook the Quinoa:** Bring quinoa and water to a boil. Reduce heat, cover, and simmer for 15 minutes until water is absorbed. Fluff with a fork.

2. **Prepare the Dressing:** Whisk together olive oil, lemon juice, a pinch of turmeric, salt, and pepper.

3. **Assemble the Bowl:** Place quinoa as the base. Arrange avocado, chickpeas, tomatoes, greens, and bell pepper in sections on top.

4. **Finish:** Drizzle with dressing and sprinkle with sesame seeds.

### Meal Prep Tips

- Cook a large batch of quinoa on Sunday for the week
- Store components separately to keep everything fresh
- The dressing keeps for up to a week in the fridge
- Add different toppings each day for variety

### Nutritional Highlights (per serving)

| Nutrient | Amount |
|----------|--------|
| Calories | 450 |
| Protein | 18g |
| Fiber | 12g |
| Healthy Fats | 22g |
| Iron | 30% DV |

---

*Find organic quinoa, extra virgin olive oil, and all the superfoods you need in our shop.*
    `,
  },
  {
    id: 6,
    title: "Khejur Jaggery vs Sugar: What's the Healthier Choice?",
    slug: "jaggery-vs-sugar",
    excerpt: "Compare date palm jaggery with refined sugar in terms of nutrition, glycemic index, and health benefits. The answer might surprise you.",
    category: "Health",
    image: blogJaggery,
    date: "Jan 8, 2026",
    readTime: "5 min read",
    content: `
## Khejur Jaggery vs. Refined Sugar

In Bengali cuisine, *khejur gur* (date palm jaggery) has been a beloved sweetener for centuries. But how does it actually compare to refined white sugar? Let's break it down.

### What is Khejur Jaggery?

Khejur jaggery is made from the sap of date palm trees, collected during winter months (November to February) in Bengal. The sap is slowly heated and reduced into a rich, amber-colored sweetener — no chemicals, no bleaching, no processing.

### Nutritional Comparison

| Property | Khejur Jaggery | Refined Sugar |
|----------|---------------|---------------|
| Calories (per 100g) | 383 | 387 |
| Iron | 11mg (61% DV) | 0mg |
| Calcium | 80mg | 1mg |
| Potassium | 450mg | 2mg |
| Magnesium | 70mg | 0mg |
| Processing | Minimal | Highly processed |
| Glycemic Index | ~55 (Medium) | ~65 (High) |

### Why Jaggery Wins

**1. Rich in Minerals:** Unlike sugar which is pure sucrose, jaggery retains iron, calcium, potassium, and magnesium from the palm sap.

**2. Lower Glycemic Index:** Jaggery causes a slower rise in blood sugar compared to refined sugar, making it a slightly better choice for blood sugar management.

**3. Traditional Medicine Benefits:** In Ayurveda, jaggery is used to aid digestion, purify blood, and boost immunity.

**4. Unprocessed Goodness:** No bleaching agents, no chemical refining — just pure, concentrated palm sap.

### How to Use Jaggery

- Sweeten your morning tea or coffee
- Add to desserts like *payesh* (rice pudding) and *sandesh*
- Create marinades and glazes for savory dishes
- Enjoy as a post-meal digestive aid

### A Word of Caution

While jaggery is a healthier alternative, it's still a form of sugar. Moderation is key — aim to use it as a replacement for refined sugar rather than adding it on top of your regular sugar intake.

---

*Try our premium Khejur Jaggery, hand-collected from date palm trees in rural Bengal.*
    `,
  },
];
