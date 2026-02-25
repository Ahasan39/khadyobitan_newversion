import { useState } from "react";
import { Star, Send } from "lucide-react";
import { toast } from "sonner";
import { useTranslation } from "react-i18next";

interface Review {
  name: string;
  rating: number;
  comment: string;
  date: string;
}

interface ReviewsTabProps {
  productId: number;
  rating: number;
  reviewsCount: number;
}

const ReviewsTab = ({ productId, rating, reviewsCount }: ReviewsTabProps) => {
  const { t } = useTranslation();
  const storageKey = `reviews-${productId}`;
  const [reviews, setReviews] = useState<Review[]>(() => {
    try {
      return JSON.parse(localStorage.getItem(storageKey) || "[]");
    } catch {
      return [];
    }
  });

  const [name, setName] = useState("");
  const [comment, setComment] = useState("");
  const [hoverRating, setHoverRating] = useState(0);
  const [selectedRating, setSelectedRating] = useState(0);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    const trimmedName = name.trim();
    const trimmedComment = comment.trim();

    if (!trimmedName || !trimmedComment || selectedRating === 0) {
      toast.error("Please fill in all fields and select a rating.");
      return;
    }
    if (trimmedName.length > 100) {
      toast.error("Name must be less than 100 characters.");
      return;
    }
    if (trimmedComment.length > 1000) {
      toast.error("Comment must be less than 1000 characters.");
      return;
    }

    const newReview: Review = {
      name: trimmedName,
      rating: selectedRating,
      comment: trimmedComment,
      date: new Date().toLocaleDateString("en-US", { year: "numeric", month: "short", day: "numeric" }),
    };

    const updated = [newReview, ...reviews];
    setReviews(updated);
    localStorage.setItem(storageKey, JSON.stringify(updated));
    setName("");
    setComment("");
    setSelectedRating(0);
    toast.success("Thank you for your review!");
  };

  const allReviews = reviews;
  const avgRating = allReviews.length > 0
    ? (allReviews.reduce((sum, r) => sum + r.rating, 0) / allReviews.length).toFixed(1)
    : rating;

  return (
    <div className="max-w-2xl space-y-8">
      <div className="flex items-center gap-4 pb-6 border-b border-border/50">
        <div className="text-center">
          <p className="font-heading text-4xl font-bold text-foreground">{avgRating}</p>
          <div className="flex gap-0.5 mt-1">
            {Array.from({ length: 5 }).map((_, i) => (
              <Star key={i} className={`h-4 w-4 ${i < Math.round(Number(avgRating)) ? "text-accent fill-accent" : "text-border"}`} />
            ))}
          </div>
          <p className="font-body text-xs text-muted-foreground mt-1">
            {reviewsCount + allReviews.length} {t("product.reviews")}
          </p>
        </div>
      </div>

      <form onSubmit={handleSubmit} className="p-5 bg-muted/40 rounded-xl border border-border/40 space-y-4">
        <p className="font-heading text-base font-semibold text-foreground">{t("product.writeReview")}</p>

        <div>
          <p className="font-body text-xs font-medium text-muted-foreground mb-1.5">{t("product.yourRating")}</p>
          <div className="flex gap-1">
            {Array.from({ length: 5 }).map((_, i) => (
              <button
                key={i}
                type="button"
                onMouseEnter={() => setHoverRating(i + 1)}
                onMouseLeave={() => setHoverRating(0)}
                onClick={() => setSelectedRating(i + 1)}
                className="p-0.5 transition-transform hover:scale-110"
              >
                <Star
                  className={`h-6 w-6 transition-colors ${
                    i < (hoverRating || selectedRating)
                      ? "text-accent fill-accent"
                      : "text-border"
                  }`}
                />
              </button>
            ))}
          </div>
        </div>

        <div>
          <label className="font-body text-xs font-medium text-muted-foreground mb-1.5 block">{t("product.name")}</label>
          <input
            type="text"
            value={name}
            onChange={(e) => setName(e.target.value)}
            placeholder={t("product.yourName")}
            maxLength={100}
            className="w-full px-3 py-2.5 rounded-lg border border-border bg-background font-body text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"
          />
        </div>

        <div>
          <label className="font-body text-xs font-medium text-muted-foreground mb-1.5 block">{t("product.comment")}</label>
          <textarea
            value={comment}
            onChange={(e) => setComment(e.target.value)}
            placeholder={t("product.sharExperience")}
            maxLength={1000}
            rows={3}
            className="w-full px-3 py-2.5 rounded-lg border border-border bg-background font-body text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all resize-none"
          />
          <p className="font-body text-[10px] text-muted-foreground text-right mt-0.5">{comment.length}/1000</p>
        </div>

        <button
          type="submit"
          className="px-5 py-2.5 bg-primary text-primary-foreground rounded-lg font-body text-sm font-semibold flex items-center gap-2 hover:opacity-90 transition-all shadow-sm"
        >
          <Send className="h-3.5 w-3.5" /> {t("product.submitReview")}
        </button>
      </form>

      {allReviews.length > 0 && (
        <div className="space-y-4">
          {allReviews.map((r, i) => (
            <div key={i} className="p-4 bg-card rounded-xl border border-border/40">
              <div className="flex items-center justify-between mb-2">
                <div>
                  <p className="font-body text-sm font-semibold text-foreground">{r.name}</p>
                  <div className="flex gap-0.5 mt-0.5">
                    {Array.from({ length: 5 }).map((_, j) => (
                      <Star key={j} className={`h-3 w-3 ${j < r.rating ? "text-accent fill-accent" : "text-border"}`} />
                    ))}
                  </div>
                </div>
                <span className="font-body text-[10px] text-muted-foreground">{r.date}</span>
              </div>
              <p className="font-body text-sm text-muted-foreground leading-relaxed">{r.comment}</p>
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

export default ReviewsTab;
