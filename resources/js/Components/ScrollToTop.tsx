import React from 'react';
import { useEffect } from "react";
import { usePage } from "@inertiajs/react";

const ScrollToTop = () => {
  const { url } = usePage();

  useEffect(() => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  }, [url]);

  return null;
};

export default ScrollToTop;
