import { useTranslation } from "react-i18next";
import { Globe } from "lucide-react";

const LanguageSwitcher = () => {
  const { i18n } = useTranslation();
  const currentLang = i18n.language?.startsWith("bn") ? "bn" : "en";

  const toggle = () => {
    i18n.changeLanguage(currentLang === "en" ? "bn" : "en");
  };

  return (
    <button
      onClick={toggle}
      className="flex items-center gap-1.5 px-2.5 py-1.5 rounded-full text-xs font-body font-medium hover:bg-muted transition-colors text-foreground/75"
      aria-label="Switch language"
    >
      <Globe className="h-4 w-4" />
      <span className="hidden sm:inline">{currentLang === "en" ? "বাং" : "EN"}</span>
    </button>
  );
};

export default LanguageSwitcher;
